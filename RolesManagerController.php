<?php

/**
  *  Roles Manager plugin pre-release for Wolf CMS
  *  Available on the forum
  * 
  *  Manage Roles and assign/remove permissions.
  *
  *  @author andrewmman <andrewmman@gmail.com>
  *  @package Plugins
  *  @subpackage roles_manager
  *  @version 0.0.1
  *  @copyright andrewmman, 2011
  *  @license http://www.gnu.org/licenses/gpl.html GPLv3 license
  */

if (!defined('IN_CMS')) { exit(); }


class RolesManagerController extends PluginController {

    const PLUGIN_ID = 'roles_manager';

    public function __construct() {
        if (defined('CMS_BACKEND')) {
            AuthUser::load();
            if (!AuthUser::isLoggedIn()) {
                redirect(get_url('login'));
            }
            else if (!AuthUser::hasPermission('admin_edit')) {
                Flash::set('error', __('You do not have permission to access the requested page!'));
                redirect(get_url());
            }

			$this->setLayout('backend');
            $this->assignToLayout('sidebar', new View( PLUGINS_ROOT.DS.self::PLUGIN_ID.DS.'views/sidebar'));
		}
    }

	public function index() {
	    $roles = Role::findAllFrom('Role');

        foreach($roles as $role) {
	        $role->users = $this->_getUserCount($role->id);
	    }

	    $this->display('index', array( 'roles' => $roles ));
	}

	public function documentation() {
	    // Check for localized documentation or fallback to the default english and display notice
        $lang = ( $user = AuthUser::getRecord() ) ? strtolower($user->language) : 'en';

        if( !file_exists( PLUGINS_ROOT.DS.self::PLUGIN_ID.DS.'views/documentation/'.$lang.'.php') ) {            
            $message = __("There's no translation for the documentation in your language, displaying the default english.");
            $this->display('documentation/en', array( 'message' => $message ));
        }
        else
            $this->display('documentation/'.$lang);
	}

/*---------------------------------------------------------------------------
                          - ROLES actions -
  ---------------------------------------------------------------------------*/

    public function add() {
        // Trying to save?
        if( get_request_method() == 'POST' ) {
            return $this->_save('add');
        }

        // Instantiate and display the form
        $role = new Role;
        $token = $this->_getTokenFor('add');
        $permissions = Permission::getPermissions();
        $role_permissions = array();
        $permissions_disabled = array();

        // New Role doesn't have any permissions
        foreach ($permissions as $perm) {
            array_push($permissions_disabled, $perm->id);
        }
        $this->display('edit', array(
                'action' => 'add',
                'csrf_token' => $token,
                'role' => $role,
                'permissions' => $permissions,
                'role_permissions' => $role_permissions,
                'permissions_disabled' => $permissions_disabled
            )
        );
    }

    public function edit($id) {
        if (!is_numeric($id)) {
            Flash::set('error', __('The Role <strong>ID</strong> is not valid!'));
            $this->_redirectTo();
        }

        // Trying to save?
        if( get_request_method() == 'POST' ) {
            return $this->_save('edit');
        }

        $role = Role::findById( (int) $id );

        if(!$role) {
            Flash::set('error', 'Role not found!');
            $this->_redirectTo();
        }

        // Set data
        $permissions = Permission::getPermissions();
        $role_permissions = array();
        $permissions_disabled = array();

        foreach ($permissions as $perm) {
            if( $role->id == 1 || $role->hasPermission($perm) )
                array_push($role_permissions, $perm->id);
            else
                array_push($permissions_disabled, $perm->id);
        }

        $token = $this->_getTokenFor('edit');

        // Display the form and pass the data to the form
        $this->display('edit', array(
                'action' => 'edit',
                'csrf_token' => $token,
                'role' => $role,
                'permissions' => $permissions,
                'role_permissions' => $role_permissions,
                'permissions_disabled' => $permissions_disabled
            )
        );
    }

    private function _save($action) {
        $data = $_POST['role'];

        // Simple Validation
        $errors = false;        
        use_helper('Validate');

        // CSRF checks
        if (isset($_POST['csrf_token'])) {
            $csrf_token = $_POST['csrf_token'];
            if( ! $this->_isTokenValid($csrf_token,$action) )
                $errors[] = __('Invalid CSRF token found!');
        }
        else {
            $errors[] = __('No CSRF token found!');
        }

    	if( $action == 'edit' && empty($data['id']) ) {
            $errors[] = __('The Role <strong>ID</strong> is not valid!');
        }
        $data['id'] = ($action == 'add') ? '' : (int) $data['id'] ;

        $data['name'] = trim($data['name']);
        if( empty($data['name']) ) {
            $errors[] = __("You must specify the Role's <strong>name</strong>!");
        }
    	// End Validation

    	// Protect the administrator role permissions
        $admin_changed = false;

        $role_perms = $data['role_permissions'];
    	$new_permissions = array();

    	foreach($role_perms as $i => $rp) {

    	    if( ($data['id'] === 1) || (isset($rp['assigned']) && $rp['assigned'] == 'on' )) {
    	        $perm_data = array(
    	           'id' => (int)$rp['permission_id'],
    	           'name' => trim($rp['permission_name'])
    	        );
    	        $p = new Permission($perm_data);
                $new_permissions[$rp['permission_name']] = $p;
                
                if( (!isset($rp['assigned']) ) || $rp['assigned'] != 'on') {
                    $admin_changed = true;
                }
            }
    	}

        $role_data = array(
            'id' => $data['id'],
            'name' => $data['name'],
            'permissions' => $new_permissions
        );

        $role = new Role($role_data);
        
        // Inform the user if he tried to remove permissions from the 'administrator' role
        if (false !== $admin_changed) {
            $errors[] = __("Permissions <strong>can't</strong> be removed from the <strong>':name'</strong> role!", array( ':name' => $role->name ));
        }
        
        if (false !== $errors) {
            // Set the errors to be displayed.
            Flash::setNow('error', implode('<br/>', $errors));
            // Get the data
            $token = $this->_getTokenFor($action);
            $permissions = Permission::getPermissions();
            $role_permissions = array();
            $permissions_disabled = array();

            foreach ($permissions as $perm) {
                if($role->hasPermission($perm))
                    array_push($role_permissions, $perm->id);
                else
                    array_push($permissions_disabled, $perm->id);
            }

            $this->display('edit', array(
                    'action' => $action,
                    'csrf_token' => $token,
                    'role' => $role,
                    'permissions' => $permissions,
                    'role_permissions' => $role_permissions,
                    'permissions_disabled' => $permissions_disabled
                )
            );
        }
        else {
            if( !$role->save() ) {
                Flash::set('post_role', (object) $role_data);
                Flash::set('error', __('The <strong>:name</strong> role could not be saved!', array( ':name' => $role->name )));
                $action_url = ($action == 'add') ? 'add' : 'edit/'.$role->id ;

                // Notify
                $message = __(':username tried to :action the <strong>:name</strong> role, but it couldn\'t be saved!', array(
                    ':name' => ($action == 'add') ? $role->name : sprintf('<a href="%s">%s</a>', get_url('plugin/roles_manager/edit/'.$role->id), $role->name),
                    ':action' => ($action == 'add') ? 'add' : 'edit'
                ));
                Observer::notify('log_event', $message, 'roles_manager', 3);

                $this->_redirectTo($action_url);
            }
            else {
                if( RolePermission::savePermissionsFor( (int)$role->id, $role->permissions ) ) {
                    Flash::set( 'success', __('The <strong>:name</strong> role was saved succesfully!', array( ':name' => $role->name )) );

                    // Notify
                    $message = __('Role <strong>:name</strong> was :action by :username.', array(
                        ':name' => sprintf('<a href="%s">%s</a>', get_url('plugin/roles_manager/edit/'.$role->id), $role->name),
                        ':action' => ($action == 'add') ? 'added' : 'revised'
                    ));
                    Observer::notify('log_event', $message, 'roles_manager', 5);

                    $this->_redirectTo();
                }
                else {
                    Flash::set('post_role', (object) $role_data);
                    Flash::set( 'error', __('The <strong>:name</strong> role <strong>permissions</strong> could not be saved!', array( ':name' => $role->name )) );
                    $action_url = ($submit == 'add') ? 'add' : 'edit/' . $role->id;

                    // Notify
                    $message = __(':username tried to :action the <strong>:name</strong> role, but it couldn\'t be saved!', array(
                        ':name' => ($action == 'add') ? $role->name : sprintf('<a href="%s">%s</a>', get_url('plugin/roles_manager/edit/'.$role->id), $role->name),
                        ':action' => ($action == 'add') ? 'add' : 'edit'
                    ));
                    Observer::notify('log_event', $message, 'roles_manager', 3);

                    $this->_redirectTo($action_url);
                }
            }
        }

	}

    public function delete($id) {
        // Protect the administrator role
        if( (int)$id == 1 ) {
            $admin_role = Role::findById(1);
            $admin_role_name = ($admin_role) ? $admin_role->name : 'administrator';
            Flash::set( 'error', __("You can't delete the '<strong>:name</strong>' role!", array( ':name' => $admin_role_name )) );
            $this->_redirectTo();
        }

        if($role = Role::findById($id)) {
            $permissions = RolePermission::findPermissionsFor((int)$role->id);
            // Set data in case something goes wrong
            Flash::set( 'role', (object) $role );
            Flash::set( 'role_permissions', (object) $permissions );

            // First delete the role_permissions relationship
            if( RolePermission::savePermissionsFor( (int)$role->id, $role->permissions ) ) {

                // Now let's try deleting the role
                if( $role->delete() ) {

                    // Notify
                    $message = __('Role <strong>:name</strong> was deleted by :username.', array( ':name' => $role->name ));
                    Observer::notify('log_event', $message, 'roles_manager', 5);

                    Flash::set('success', __('Role <strong>:name</strong> has been deleted!', array( ':name' => $role->name )));
                }
                else {
                    // Rebuild role_permissions relationships
                    $permissions = Flash::get('role_permissions');
                    RolePermission::savePermissionsFor( (int)$role->id, $role->permissions );

                    // Notify
                    $message = __(':username tried to delete the <strong>:name</strong> role, but something went wrong!', array(
                        ':name' => sprintf('<a href="%s">%s</a>', get_url('plugin/roles_manager/edit/'.$role->id), $role->name),
                    ));
                    Observer::notify('log_event', $message, 'roles_manager', 3);

                    Flash::set('error', __('Role <strong>:name</strong> could not be deleted!', array( ':name' => $role->name )));
                }
            }
            else {
                // Notify
                $message = __(':username tried to delete the <strong>:name</strong> role, but something went wrong!', array(
                    ':name' => sprintf('<a href="%s">%s</a>', get_url('plugin/roles_manager/edit/'.$role->id), $role->name),
                ));
                Observer::notify('log_event', $message, 'roles_manager', 3);

                Flash::set( 'error', __('The :name role <strong>permissions</strong> could not be deleted!', array( ':name' => $role->name )));
            }
        }
        else {
            Flash::set('error', __('Role not found!'));
        }

        $this->_redirectTo();
    }

    /**
      * @overwrite
      */
    public function render($view, $vars=array()) {
        if (defined('CMS_BACKEND')) {
            if ($this->layout) {
                $this->layout_vars['content_for_layout'] = new View( PLUGINS_ROOT.DS.self::PLUGIN_ID.DS.'views'.DS.$view, $vars);
                return new View('../layouts/'.$this->layout, $this->layout_vars);
            }
            else
                return new View(PLUGINS_ROOT.DS.self::PLUGIN_ID.DS.'views'.DS.$view, $vars);
        }
        else
            redirect(get_url('login'));
    }

    /*---------------------------------------------------------------------------
                                   - HELPERS -
    ---------------------------------------------------------------------------*/

    private function _redirectTo($action='index') {
        $url = 'plugin/'.self::PLUGIN_ID.'/'.$action;
        redirect( get_url($url) );
    }

    private function _getTokenFor($action) {
        $url = BASE_URL.'plugin/'.self::PLUGIN_ID.'/'.$action;
        return SecureToken::generateToken($url);
    }

    private function _isTokenValid($token, $action) {
        $url = BASE_URL.'plugin/'.self::PLUGIN_ID.'/'.$action;
        return (boolean) SecureToken::validateToken($token, $url);
    }

    private function _getUserCount($rid) {
        $sql = "SELECT COUNT(user_id) FROM ".TABLE_PREFIX."user_role WHERE role_id=".(int)$rid;
        $stmt = Record::getConnection()->prepare($sql);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    private function _getUsersByRole(Role $role) {
        if(!($role instanceof Role))
            return false;

        $sql = "SELECT user_id FROM ".TABLE_PREFIX."user_role WHERE role_id=".(int)$role->id;
        $stmt = Record::getConnection()->prepare($sql);
        $stmt->execute();

        $users = array();
        while( $uid = $stmt->fetchColumn()) {
            $user = User::findById($uid);
            if($user !== false) {
                unset($user->password);
                unset($user->salt);
                $users[] = $user;
            }
        }
        return $users;
    }

    private function _removeRoleFromUser($rid,$uid) {
        $tablename = Record::tableNameFromClassName('UserRole');
        $sql = 'DELETE FROM '.$tablename.' WHERE role_id='.(int)$rid.' AND user_id='.(int)$uid;
        $pdo = Record::getConnection();
        return $pdo->exec($sql) !== false;
    }

    // Not used but keep it
    /*
    private static function _getCorePermissions() {
        return array(
            'admin_view',
            'admin_edit',
            'user_view',
            'user_add',
            'user_edit',
            'user_delete',
            'layout_view',
            'layout_add',
            'layout_edit',
            'layout_delete',
            'snippet_view',
            'snippet_add',
            'snippet_edit',
            'snippet_delete',
            'page_view',
            'page_add',
            'page_edit',
            'page_delete'
        );
    }
    */

}