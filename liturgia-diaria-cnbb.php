<?php
/**
 * Plugin Name: Liturgia Diária CNBB
 * Plugin URI:  https://github.com/JosueSantos/wp-liturgia-diaria-plugin
 * Description: Exibe a liturgia diária (leituras, salmo, evangelho) com base em APIs públicas da CNBB. Inclui shortcodes [liturgia_diaria_cnbb] e [liturgia_diaria].
 * Version:     1.0.0
 * Author:      Josué Santos
 * Author URI:  https://josuesantos.github.io/
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: liturgia-diaria-cnbb
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Segurança: bloqueia acesso direto
}

// Definir caminho do plugin
define( 'LITURGIA_DIARIA_CNBB_PATH', plugin_dir_path( __FILE__ ) );

// Carregar shortcodes
require_once LITURGIA_DIARIA_CNBB_PATH . 'includes/shortcode-liturgia.php';

