<?php
/**
 * Plugin Name: Agent-X: AI Crew Engine
 * Plugin URI: https://github.com/torukokappadokia/agent-x-wp
 * Description: Deploy your own AI content army. Local LLM powered multi-agent system for WordPress.
 * Version: 0.1.0
 * Author: torukokappadokia
 * License: GPL v3 or later
 * Requires at least: 5.8
 * Requires PHP: 7.4
 */

if (!defined('ABSPATH')) {
    exit;
}

define('AGENTX_VERSION', '0.1.0');
define('AGENTX_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('AGENTX_PLUGIN_URL', plugin_dir_url(__FILE__));

class AgentX_Core {
    
    private static $instance = null;
    
    public static function instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        add_action('admin_menu', array($this, 'add_menu'));
        add_action('admin_enqueue_scripts', array($this, 'add_scripts'));
        add_action('wp_ajax_agentx_check', array($this, 'check_ollama'));
    }
    
    public function add_menu() {
        add_menu_page(
            'Agent-X',
            'Agent-X',
            'manage_options',
            'agent-x',
            array($this, 'show_page'),
            'dashicons-groups',
            30
        );
    }
    
    public function add_scripts($hook) {
        if ($hook !== 'toplevel_page_agent-x') return;
        
        wp_enqueue_style('agentx-css', AGENTX_PLUGIN_URL . 'assets/css/admin.css');
        wp_enqueue_script('agentx-js', AGENTX_PLUGIN_URL . 'assets/js/admin.js', array('jquery'));
        
        wp_localize_script('agentx-js', 'agentx_ajax', array(
            'url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('agentx_nonce')
        ));
    }
    
    public function show_page() {
        echo '<div class="wrap">';
        echo '<h1>🚀 Agent-X</h1>';
        echo '<div class="agentx-box">';
        echo '<h2>Adım 1: Ollama Bağlantısı</h2>';
        echo '<button id="check-btn" class="button button-primary">Bağlantıyı Kontrol Et</button>';
        echo '<div id="status"></div>';
        echo '</div>';
        echo '</div>';
    }
    
    public function check_ollama() {
        check_ajax_referer('agentx_nonce', 'nonce');
        
        $response = wp_remote_get('http://localhost:11434/api/tags', array('timeout' => 5));
        
        if (is_wp_error($response)) {
            wp_send_json_error('Ollama çalışmıyor');
        }
        
        wp_send_json_success('Bağlandı!');
    }
}

AgentX_Core::instance();
