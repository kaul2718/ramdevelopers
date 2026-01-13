import { usePage } from '@inertiajs/vue3';

export const useUserRole = () => {
    const page = usePage();

    const getCurrentUserRole = () => {
        const user = page.props.auth?.user;
        if (!user?.roles || user.roles.length === 0) return null;
        return user.roles[0].name;
    };

    const getRoleTitle = (role = null) => {
        const userRole = role || getCurrentUserRole();
        const roleMap = {
            'Admin': 'Administrador',
            'DevAdmin': 'Desarrollador',
            'Master Pais': 'Master País',
            'Agente Inmobiliario': 'Agente Inmobiliario',
            'Cliente': 'Cliente'
        };
        return roleMap[userRole] || 'Dashboard';
    };

    const isAdmin = () => getCurrentUserRole() === 'Admin';
    const isDevAdmin = () => getCurrentUserRole() === 'DevAdmin';
    const isMasterPais = () => getCurrentUserRole() === 'Master Pais';
    const isAgenteInmobiliario = () => getCurrentUserRole() === 'Agente Inmobiliario';
    const isCliente = () => getCurrentUserRole() === 'Cliente';

    return {
        currentRole: getCurrentUserRole(),
        getCurrentUserRole,
        getRoleTitle,
        isAdmin,
        isDevAdmin,
        isMasterPais,
        isAgenteInmobiliario,
        isCliente
    };
};
