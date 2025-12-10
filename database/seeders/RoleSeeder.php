<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role_admin    = Role::create(['name' => 'admin']);
        $role_editor   = Role::create(['name' => 'editor']);
        $role_promotor = Role::create(['name' => 'promotor']);
        $role_captador = Role::create(['name' => 'captador']);

        $permission_create_role = Permission::create(['name' => 'create roles']);
        $permission_read_role   = Permission::create(['name' => 'read roles']);
        $permission_update_role = Permission::create(['name' => 'update roles']);
        $permission_delete_role = Permission::create(['name' => 'delete roles']);

        $permission_create_approvalStatus = Permission::create(['name' => 'create approval status']);
        $permission_read_approvalStatus   = Permission::create(['name' => 'read approval status']);
        $permission_update_approvalStatus = Permission::create(['name' => 'update approval status']);
        $permission_delete_approvalStatus = Permission::create(['name' => 'delete approval status']);

        $permission_create_businessState = Permission::create(['name' => 'create business state']);
        $permission_read_businessState   = Permission::create(['name' => 'read business state']);
        $permission_update_businessState = Permission::create(['name' => 'update business state']);
        $permission_delete_businessState = Permission::create(['name' => 'delete business state']);

        $permission_create_city = Permission::create(['name' => 'create city']);
        $permission_read_city   = Permission::create(['name' => 'read city']);
        $permission_update_city = Permission::create(['name' => 'update city']);
        $permission_delete_city = Permission::create(['name' => 'delete city']);

        $permission_create_country = Permission::create(['name' => 'create countries']);
        $permission_read_country   = Permission::create(['name' => 'read countries']);
        $permission_update_country = Permission::create(['name' => 'update countries']);
        $permission_delete_country = Permission::create(['name' => 'delete countries']);

        $permission_create_developer = Permission::create(['name' => 'create developer']);
        $permission_read_developer   = Permission::create(['name' => 'read developer']);
        $permission_update_developer = Permission::create(['name' => 'update developer']);
        $permission_delete_developer = Permission::create(['name' => 'delete developer']);

        $permission_create_development = Permission::create(['name' => 'create development']);
        $permission_read_development   = Permission::create(['name' => 'read development']);
        $permission_update_development = Permission::create(['name' => 'update development']);
        $permission_delete_development = Permission::create(['name' => 'delete development']);

        $permission_create_developmentCaptor = Permission::create(['name' => 'create development captors']);
        $permission_read_developmentCaptor   = Permission::create(['name' => 'read development captors']);
        $permission_update_developmentCaptor = Permission::create(['name' => 'update development captors']);
        $permission_delete_developmentCaptor = Permission::create(['name' => 'delete development captors']);

        $permission_create_developmentFile = Permission::create(['name' => 'create development files']);
        $permission_read_developmentFile   = Permission::create(['name' => 'read development files']);
        $permission_update_developmentFile = Permission::create(['name' => 'update development files']);
        $permission_delete_developmentFile = Permission::create(['name' => 'delete development files']);

        $permission_create_developmentImage = Permission::create(['name' => 'create development images']);
        $permission_read_developmentImage   = Permission::create(['name' => 'read development images']);
        $permission_update_developmentImage = Permission::create(['name' => 'update development images']);
        $permission_delete_developmentImage = Permission::create(['name' => 'delete development images']);

        $permission_create_documentType = Permission::create(['name' => 'create document types']);
        $permission_read_documentType   = Permission::create(['name' => 'read document types']);
        $permission_update_documentType = Permission::create(['name' => 'update document types']);
        $permission_delete_documentType = Permission::create(['name' => 'delete document types']);

        $permission_create_lead = Permission::create(['name' => 'create leads']);
        $permission_read_lead   = Permission::create(['name' => 'read leads']);
        $permission_update_lead = Permission::create(['name' => 'update leads']);
        $permission_delete_lead = Permission::create(['name' => 'delete leads']);

        $permission_create_leadNote = Permission::create(['name' => 'create lead notes']);
        $permission_read_leadNote   = Permission::create(['name' => 'read lead notes']);
        $permission_update_leadNote = Permission::create(['name' => 'update lead notes']);
        $permission_delete_leadNote = Permission::create(['name' => 'delete lead notes']);

        $permission_create_leadSource = Permission::create(['name' => 'create lead sources']);
        $permission_read_leadSource   = Permission::create(['name' => 'read lead sources']);
        $permission_update_leadSource = Permission::create(['name' => 'update lead sources']);
        $permission_delete_leadSource = Permission::create(['name' => 'delete lead sources']);

        $permission_create_leadStatus = Permission::create(['name' => 'create leads status']);
        $permission_read_leadStatus   = Permission::create(['name' => 'read lead status']);
        $permission_update_leadStatus = Permission::create(['name' => 'update lead status']);
        $permission_delete_leadStatus = Permission::create(['name' => 'delete lead status']);

        $permission_create_membership = Permission::create(['name' => 'create memberships']);
        $permission_read_membership   = Permission::create(['name' => 'read memberships']);
        $permission_update_membership = Permission::create(['name' => 'update memberships']);
        $permission_delete_membership = Permission::create(['name' => 'delete memberships']);

        $permission_create_user = Permission::create(['name' => 'create users']);
        $permission_read_user   = Permission::create(['name' => 'read users']);
        $permission_update_user = Permission::create(['name' => 'update users']);
        $permission_delete_user = Permission::create(['name' => 'delete users']);

        $permissions_admin = [
            $permission_create_role,
            $permission_read_role,
            $permission_update_role,
            $permission_delete_role,

            $permission_create_approvalStatus,
            $permission_read_approvalStatus,
            $permission_update_approvalStatus,
            $permission_delete_approvalStatus,

            $permission_create_businessState,
            $permission_read_businessState,
            $permission_update_businessState,
            $permission_delete_businessState,

            $permission_create_city,
            $permission_read_city,
            $permission_update_city,
            $permission_delete_city,

            $permission_create_country,
            $permission_read_country,
            $permission_update_country,
            $permission_delete_country,

            $permission_create_developer,
            $permission_read_developer,
            $permission_update_developer,
            $permission_delete_developer,

            $permission_create_development,
            $permission_read_development,
            $permission_update_development,
            $permission_delete_development,

            $permission_create_developmentCaptor,
            $permission_read_developmentCaptor,
            $permission_update_developmentCaptor,
            $permission_delete_developmentCaptor,

            $permission_create_developmentFile,
            $permission_read_developmentFile,
            $permission_update_developmentFile,
            $permission_delete_developmentFile,

            $permission_create_developmentImage,
            $permission_read_developmentImage,
            $permission_update_developmentImage,
            $permission_delete_developmentImage,

            $permission_create_documentType,
            $permission_read_documentType,
            $permission_update_documentType,
            $permission_delete_documentType,

            $permission_create_lead,
            $permission_read_lead,
            $permission_update_lead,
            $permission_delete_lead,

            $permission_create_leadNote,
            $permission_read_leadNote,
            $permission_update_leadNote,
            $permission_delete_leadNote,

            $permission_create_leadSource,
            $permission_read_leadSource,
            $permission_update_leadSource,
            $permission_delete_leadSource,

            $permission_create_leadStatus,
            $permission_read_leadStatus,
            $permission_update_leadStatus,
            $permission_delete_leadStatus,

            $permission_create_membership,
            $permission_read_membership,
            $permission_update_membership,
            $permission_delete_membership,

            $permission_create_user,
            $permission_read_user,
            $permission_update_user,
            $permission_delete_user,
        ];

        // Asignar permisos al rol editor
        $permissions_editor =[
            $permission_read_role,

            $permission_read_approvalStatus,

            $permission_read_businessState,

            $permission_read_city,

            $permission_read_country,

            $permission_create_developer,
            $permission_read_developer,
            $permission_update_developer,

            $permission_create_development,
            $permission_read_development,
            $permission_update_development,
            $permission_delete_development,

            $permission_create_developmentCaptor,
            $permission_read_developmentCaptor,
            $permission_update_developmentCaptor,
            $permission_delete_developmentCaptor,

            $permission_create_developmentFile,
            $permission_read_developmentFile,
            $permission_update_developmentFile,
            $permission_delete_developmentFile,

            $permission_create_developmentImage,
            $permission_read_developmentImage,
            $permission_update_developmentImage,
            $permission_delete_developmentImage,

            $permission_read_documentType,

            $permission_create_lead,
            $permission_read_lead,
            $permission_update_lead,

            $permission_create_leadNote,
            $permission_read_leadNote,
            $permission_update_leadNote,

            $permission_read_leadSource,

            $permission_read_leadStatus,

            $permission_read_membership,

            $permission_read_user,
            $permission_update_user,
        ];

// Asignar permisos al rol promotor
         $permissions_promotor =[
            $permission_read_development,

            $permission_read_developmentCaptor,

            $permission_read_developmentFile,

            $permission_read_developmentImage,

            $permission_read_documentType,

            $permission_create_lead,
            $permission_read_lead,
            $permission_update_lead,

            $permission_create_leadNote,
            $permission_read_leadNote,
            $permission_update_leadNote,

            $permission_read_leadSource,

            $permission_read_leadStatus,

            $permission_read_membership,
        ];

// Asignar permisos al rol captador
         $permissions_captador =[
            $permission_read_development,

            $permission_read_developmentFile,

            $permission_read_developmentImage,

            $permission_read_documentType,

            $permission_create_lead,
            $permission_read_lead,

            $permission_create_leadNote,
            $permission_read_leadNote,

            $permission_read_leadSource,

            $permission_read_leadStatus,
        ];
        $role_admin ->syncPermissions($permissions_admin );
        $role_editor ->syncPermissions( $permissions_editor );
        $role_captador ->syncPermissions( $permissions_captador );
        $role_promotor ->syncPermissions( $permissions_promotor );
    }
}
