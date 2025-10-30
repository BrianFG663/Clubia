import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    server: {
        https: false, // solo si tenés certificado local
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/caja-diaria.css',
                'resources/css/cobroFacturas.css',
                'resources/css/facturaProveedores.css',
                'resources/css/facturas.css',
                'resources/css/grupo-familiar.css',
                'resources/css/inscribirSocioActividad.css',
                'resources/css/notaCredito.css',
                'resources/css/panel-subactividades.css',
                'resources/css/parametros.css',
                'resources/css/registrar-ordendes.css',
                'resources/css/registrar-ventas.css',
                'resources/css/registroCajasDiarias.css',
                'resources/css/sweet-alert.css',
                'resources/css/socios-card.css',
                'resources/css/panelSocios.css',
                'resources/css/loginSocio.css',
                'resources/css/CambioContrasena.css',
                'resources/css/carnetSocio.css',
                'resources/css/moderarFotos.css',
                'resources/css/personalizarLogo.css',
                'resources/css/splash.css',
                'resources/js/app.js',
                'resources/js/bootstrap.js',
                'resources/js/cajaDiaria.js',
                'resources/js/moderarFotos.js',
                'resources/js/cobroFacturas.js',
                'resources/js/facturaProveedores.js',
                'resources/js/facturas.js',
                'resources/js/grupo-familiar.js',
                'resources/js/inscribirSocioActividad.js',
                'resources/js/notaCredito.js',
                'resources/js/panel-subactividades.js',
                'resources/js/parametros.js',
                'resources/js/registrar-ordenes.js',
                'resources/js/registrar-ventas.js',
                'resources/js/registroCajasDiarias.js',
                'resources/js/PanelSocios.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
