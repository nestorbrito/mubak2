<?php
/** 
 * Configuración básica de WordPress.
 *
 * Este archivo contiene las siguientes configuraciones: ajustes de MySQL, prefijo de tablas,
 * claves secretas, idioma de WordPress y ABSPATH. Para obtener más información,
 * visita la página del Codex{@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} . Los ajustes de MySQL te los proporcionará tu proveedor de alojamiento web.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** Ajustes de MySQL. Solicita estos datos a tu proveedor de alojamiento web. ** //
/** El nombre de tu base de datos de WordPress */
define('DB_NAME', 'mubak');

/** Tu nombre de usuario de MySQL */
define('DB_USER', 'root');

/** Tu contraseña de MySQL */
define('DB_PASSWORD', 'root');

/** Host de MySQL (es muy probable que no necesites cambiarlo) */
define('DB_HOST', '127.0.0.1');

/** Codificación de caracteres para la base de datos. */
define('DB_CHARSET', 'utf8mb4');

/** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
define('DB_COLLATE', '');

/**#@+
 * Claves únicas de autentificación.
 *
 * Define cada clave secreta con una frase aleatoria distinta.
 * Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
 * Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', 'B*B+2h5Il_c`tMJ.@gL3ACgX[{vat3<(f0z$o1cdr<t+EW[8_6j${ MYD:>@TcUr');
define('SECURE_AUTH_KEY', '_oDBewlbUulBG`ZP!6/w>zc @cm|j}-2;x</|6m],]Z:96>sbG?@_FI*AZI]3_Xl');
define('LOGGED_IN_KEY', 'Vtz vUo/29&hk4tNs/LtC]-+XftEv_z9f:RY-OIJF>m]nIM3sg]}1:)RW~X+@D&:');
define('NONCE_KEY', '8le#QCW5mh`?rDn2cLe*qrdGukm)K,6L1MN h`sW*ruzq;%2](/RgT%;hx#U,^z8');
define('AUTH_SALT', 'u0!k K9`LD)TBSrfwte 7< G7>j1SRWsUb__){Alur&h`vd)1E73G]q,DfPJ7V1;');
define('SECURE_AUTH_SALT', 'i[Ntj2Z${g}%mHIXZ:W~~PVRylqmt1dPA]WAg_7S$TVjcA7.8oh0<j1n7Fm{wo4i');
define('LOGGED_IN_SALT', 'nmmmHXk)4@cY n|P?1}+TC|4iD?Qb)G[s8LO#^[}297Lt<gZK<DTNEQUjZ{5C{0c');
define('NONCE_SALT', '_6RY]H;y{w!wN`iT`?=DDp.6G/8/A]Fl #mG6d[A;u8V9,>:]n%K?$hDDM+DvX+J');

/**#@-*/

/**
 * Prefijo de la base de datos de WordPress.
 *
 * Cambia el prefijo si deseas instalar multiples blogs en una sola base de datos.
 * Emplea solo números, letras y guión bajo.
 */
$table_prefix  = 'wp_';


/**
 * Para desarrolladores: modo debug de WordPress.
 *
 * Cambia esto a true para activar la muestra de avisos durante el desarrollo.
 * Se recomienda encarecidamente a los desarrolladores de temas y plugins que usen WP_DEBUG
 * en sus entornos de desarrollo.
 */
define('WP_DEBUG', false);

/* ¡Eso es todo, deja de editar! Feliz blogging */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

