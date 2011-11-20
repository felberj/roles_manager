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

    public function __construct() {
        AuthUser::load();
        if (!AuthUser::isLoggedIn()) {
            redirect(get_url('login'));
        } else if (!AuthUser::hasPermission('admin_edit')) {
            Flash::set('error', __('You do not have permission to access the requested page!'));
            redirect(get_url());
        }

		if (defined('CMS_BACKEND')) {
            define('PLUGIN_ROLESMANAGER_VIEWS_BASE', 'roles_manager/views/');
			$this->setLayout('backend');
            $this->assignToLayout('sidebar', new View('../../plugins/roles_manager/views/sidebar'));
		}
		else {
            define('PLUGIN_ROLESMANAGER_VIEWS_BASE', PLUGINS_ROOT.'/roles_manager/views/');
		}		
    }

	public function index() {
	    $roles = Role::findAllFrom('Role');
	    $this->display(
            PLUGIN_ROLESMANAGER_VIEWS_BASE . 'index',
            array( 'roles' => $roles )
        );
	}

	public function documentation() {
	    $locale = strtolower(i18n::getLocale());
	    $local_doc = $locale . '-documentation';
	    $path = PLUGINS_ROOT . '/roles_manager/views/documentation/';

	    if (!file_exists( $path . $local_doc . '.php' ))
	        $local_doc = 'en-documentation';

	    $this->display( PLUGIN_ROLESMANAGER_VIEWS_BASE . 'documentation/' . $local_doc );
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
        $token = SecureToken::generateToken( BASE_URL.'plugin/roles_manager/add' );
        $permissions = Permission::getPermissions();
        $role_permissions = array();
        $permissions_disabled = array();

        // New Role doesn't have any permissions
        foreach ($permissions as $perm) {
            array_push($permissions_disabled, $perm->id);
        }
        $this->display(
            PLUGIN_ROLESMANAGER_VIEWS_BASE . 'edit', array(
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
            redirect( get_url('plugin/roles_manager') );
        }

        // Trying to save?
        if( get_request_method() == 'POST' ) {
            return $this->_save('edit');
        }

        $role = Role::findById( (int) $id );

        if(!$role) {
            Flash::set('error', 'Role not found!');
            redirect( get_url('plugin/roles_manager') );
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

        $token = SecureToken::generateToken( BASE_URL . 'plugin/roles_manager/edit' );

        // Display the form and pass the data to the form
        $this->display(
            PLUGIN_ROLESMANAGER_VIEWS_BASE . 'edit', array(
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
            if ( !SecureToken::validateToken( $csrf_token, BASE_URL . 'plugin/roles_manager/' . $action ) ) {
                $errors[] = __('Invalid CSRF token found!');
            }
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
            $token = SecureToken::generateToken( BASE_URL . 'plugin/roles_manager/' . $action );
            $permissions = Permission::getPermissions();
            $role_permissions = array();
            $permissions_disabled = array();

            foreach ($permissions as $perm) {
                if($role->hasPermission($perm))
                    array_push($role_permissions, $perm->id);
                else
                    array_push($permissions_disabled, $perm->id);
            }

            $this->display(
                PLUGIN_ROLESMANAGER_VIEWS_BASE . 'edit',
                array(
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

                redirect( get_url('plugin/roles_manager/'.$action_url) );
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

                    redirect( get_url('plugin/roles_manager') );
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

                    redirect( get_url('plugin/roles_manager/' . $action_url) );                    
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
            redirect( get_url('plugin/roles_manager') );
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
                
        redirect( get_url('plugin/roles_manager') );
    }        

    private static function getCorePermissions() {
        $core_perms = array(
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
        
        return $core_perms;
    }
}