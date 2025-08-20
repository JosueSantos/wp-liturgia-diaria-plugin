<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Adiciona CSS do shortcode
function liturgia_diaria_enqueue_styles() {
    wp_register_style(
        'liturgia-diaria-css',
        false // não tem arquivo físico, vamos injetar o CSS inline
    );

    wp_enqueue_style('liturgia-diaria-css');

    $custom_css = "
    .ld-container {
      max-width: 800px;
      margin: 2em auto;
      padding: 2em;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.1);
      font-family: Georgia, serif;
      line-height: 1.6;
    }
    .ld-titulo {
      font-size: 1.8rem;
      color: #2c3e50;
      margin-bottom: 1em;
    }
    .ld-tempo {
      font-size: 1rem;
      margin-bottom: 2em;
      padding: 0.5em;
      background: #f4f4f4;
      border-left: 4px solid #45584d;
      border-radius: 6px;
    }
    .ld-leitura, .ld-salmo, .ld-evangelho {
      margin-bottom: 2em;
    }
    .ld-leitura-titulo {
      font-size: 1.4rem;
      color: #423a46;
      margin-bottom: 0;
    }
    .ld-referencia {
      font-style: italic;
      color: #7f8c8d;
      margin-top: 0;
      margin-bottom: 0.8em;
    }
    .ld-texto {
      margin-bottom: 0.8em;
      text-align: justify;
    }
    .ld-refrao {
      font-weight: bold;
      color: #c0392b;
      margin-bottom: 0.8em;
    }
    .ld-resposta {
      margin-top: 1em;
      font-style: italic;
      color: #34495e;
    }
    .ld-destaque-evangelho {
    }
    .ld-antifona {
      font-weight: bold;
      color: #34495e;
      margin-bottom: 1em;
    }
    ";

    wp_add_inline_style('liturgia-diaria-css', $custom_css);
}
add_action('wp_enqueue_scripts', 'liturgia_diaria_enqueue_styles');

// =========================
// Shortcode [liturgia_diaria_cnbb]
// =========================
function liturgia_diaria_cnbb_shortcode() {
    // Corrigir a data: verificar se 'dia' está no formato correto
    if (isset($_GET['dia']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['dia'])) {
        $data = sanitize_text_field($_GET['dia']);
    } else {
        $data = date('Y-m-d'); // fallback para a data atual
    }
	
	// Divide a data em ano, mês e dia
    list($ano, $mes, $dia) = explode('-', $data);

    // Monta a URL da API com os parâmetros separados
    $api_url = "https://liturgia.up.railway.app/?dia=$dia&mes=$mes&ano=$ano";

    $response = wp_remote_get($api_url);

    if (is_wp_error($response)) {
        return '<p>Não foi possível obter a liturgia do dia.</p>';
    }

    $body = wp_remote_retrieve_body($response);
    $dados = json_decode($body, true);

    if (!$dados) {
        return '<p>Erro ao processar os dados da liturgia.</p>';
    }
	
	global $liturgia_diaria_meta;
	$liturgia_diaria_meta = [
        'titulo' => 'Liturgia do Dia - ' . date('d/m/Y', strtotime($data)),
        'descricao' => sprintf(
            "Liturgia do Dia - %s | %s | %s | %s | %s",
            date('d/m/Y', strtotime($data)),
            $dados['liturgia'] ?? '',
            !empty($dados['primeiraLeitura']) ? 'Primeira Leitura (' . $dados['primeiraLeitura']['referencia'] . ')' : '',
            !empty($dados['salmo']) ? 'Salmo Responsorial (' . $dados['salmo']['referencia'] . ')' : '',
            !empty($dados['evangelho']) ? 'Evangelho (' . $dados['evangelho']['referencia'] . ')' : ''
        ),
        'data' => $dados['data'] ?? $data
    ];

    ob_start();
    ?>
    <form method="get" class="liturgia-form" style="margin-bottom:1em;">
        <label for="dia">Escolha a data:</label>
        <input type="date" id="dia" name="dia" value="<?php echo esc_attr($data); ?>">
        <button type="submit">Ver Liturgia</button>
    </form>

    <div class="liturgia-diaria">
        <h2>Liturgia do Dia - <?php echo esc_html(date('d/m/Y', strtotime($data))); ?></h2>
        <h3><?php echo esc_html($dados['liturgia']); ?></h3>

        <?php if (!empty($dados['primeiraLeitura'])): ?>
            <h4>Primeira Leitura (<?php echo esc_html($dados['primeiraLeitura']['referencia']); ?>)</h4>
            <p><?php echo nl2br(esc_html($dados['primeiraLeitura']['texto'])); ?></p>
        <?php endif; ?>

        <?php if (!empty($dados['salmo'])): ?>
            <h4>Salmo Responsorial (<?php echo esc_html($dados['salmo']['referencia']); ?>)</h4>
            <p><strong>Refrão:</strong> <?php echo esc_html($dados['salmo']['refrao']); ?></p>
            <p><?php echo nl2br(esc_html($dados['salmo']['texto'])); ?></p>
        <?php endif; ?>

        <?php if (!empty($dados['segundaLeitura']) && is_array($dados['segundaLeitura'])): ?>
            <h4>Segunda Leitura (<?php echo esc_html($dados['segundaLeitura']['referencia']); ?>)</h4>
            <p><?php echo nl2br(esc_html($dados['segundaLeitura']['texto'])); ?></p>
        <?php endif; ?>

        <?php if (!empty($dados['evangelho'])): ?>
            <h4>Evangelho (<?php echo esc_html($dados['evangelho']['referencia']); ?>)</h4>
            <p><?php echo nl2br(esc_html($dados['evangelho']['texto'])); ?></p>
        <?php endif; ?>
    </div>

    <!-- Dados Estruturados JSON-LD -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Article",
      "headline": "Liturgia do dia - <?php echo esc_html($dados['data']); ?>",
      "datePublished": "<?php echo esc_html($dados['data']); ?>",
      "author": {
        "@type": "Organization",
        "name": "CNBB"
      },
      "publisher": {
        "@type": "Organization",
        "name": "Eco da Palavra",
        "url": "<?php echo esc_url(get_site_url()); ?>"
      },
      "articleBody": "<?php echo esc_js(strip_tags($dados['primeiraLeitura']['texto'] . ' ' . $dados['salmo']['texto'] . ' ' . ($dados['segundaLeitura']['texto'] ?? '') . ' ' . $dados['evangelho']['texto'])); ?>"
    }
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('liturgia_diaria_cnbb', 'liturgia_diaria_cnbb_shortcode');

// =========================
// Shortcode [liturgia_diaria]
// =========================
function liturgia_diaria_shortcode() {
    if (isset($_GET['dia']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['dia'])) {
        $data = sanitize_text_field($_GET['dia']);
    } else {
        $data = date('Y-m-d');
    }

    $data_formatada = date('d/m/Y', strtotime($data));
    $cache_key = 'liturgia_diaria_' . $data;

    $dados_completos = get_transient($cache_key);
    if (!$dados_completos) {
        $api_url = "https://api-liturgia-diaria.vercel.app/?date=$data";
        $response = wp_remote_get($api_url);

        if (is_wp_error($response)) {
            return '<p>Não foi possível obter a liturgia do dia.</p>';
        }

        $body = wp_remote_retrieve_body($response);
        $json = json_decode($body, true);

        if (!$json || empty($json['today']['readings'])) {
            return '<p>Erro ao processar os dados da liturgia.</p>';
        }

        $dados_completos = $json['today'];
        set_transient($cache_key, $dados_completos, 12 * HOUR_IN_SECONDS);
    }

    $entry_title = $dados_completos['entry_title'] ?? '';
    $color = $dados_completos['color'] ?? '';
    $readings = $dados_completos['readings'];

    $primeira = $readings['first_reading'] ?? [];
    $segunda = $readings['second_reading'] ?? [];
    $salmo = $readings['psalm'] ?? [];
    $evangelho = $readings['gospel'] ?? [];

    ob_start();
    ?>
    <form method="get" class="liturgia-form">
        <label for="dia">Escolha a data:</label>
        <input type="date" id="dia" name="dia" value="<?php echo esc_attr($data); ?>">
        <button type="submit">Ver Liturgia</button>
    </form>

    <div class="liturgia-diaria ld-container">
        <h2 class="ld-titulo">Liturgia do Dia - <?php echo esc_html($data_formatada); ?></h2>
        
        <div class="ld-tempo">
            <div><?php echo wp_kses_post($entry_title); ?></div>
            <div><b>Cor:</b> <?php echo esc_attr($color); ?></div>
        </div>

        <?php if (!empty($primeira)) : ?>
            <div class="ld-leitura">
                <h3 class="ld-leitura-titulo"><?php echo esc_html($primeira['title']); ?></h3>
                <p><strong><?php echo esc_html($primeira['head']); ?></strong></p>
                <p class="ld-texto"><?php echo nl2br(esc_html($primeira['text'])); ?></p>
                <p class="ld-resposta"><em><?php echo esc_html($primeira['footer']); ?></em> <br><strong><?php echo esc_html($primeira['footer_response']); ?></strong></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($salmo)) : ?>
            <div class="ld-salmo">
                <h3 class="ld-leitura-titulo"><?php echo esc_html($salmo['title']); ?></h3>
                <p class="ld-refrao"><strong><?php echo esc_html($salmo['response']); ?></strong></p>
                <p class="ld-texto"><?php echo nl2br(esc_html(implode("\n", $salmo['content_psalm']))); ?></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($segunda)) : ?>
            <div class="ld-leitura">
                <h3 class="ld-leitura-titulo"><?php echo esc_html($segunda['title']); ?></h3>
                <p class="ld-referencia">1Cor 15, 20-27</p>
                <p><strong><?php echo esc_html($segunda['head']); ?></strong></p>
                <p class="ld-texto"><?php echo nl2br(esc_html($segunda['text'])); ?></p>
                <p class="ld-resposta"><em><?php echo esc_html($segunda['footer']); ?></em><br><strong><?php echo esc_html($segunda['footer_response']); ?></strong></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($evangelho)) : ?>
            <div class="ld-evangelho ld-destaque-evangelho">
                <h3 class="ld-leitura-titulo"><?php echo esc_html($evangelho['head_title']); ?></h3>
                <p class="ld-antifona"><strong><?php echo esc_html($evangelho['head']); ?></strong></p>
                <p class="ld-antifona"><strong><?php echo esc_html($evangelho['head_response']); ?></strong></p>
                <p class="ld-texto"><?php echo nl2br(esc_html($evangelho['text'])); ?></p>
                <p class="ld-resposta"><em><?php echo esc_html($evangelho['footer']); ?></em><br><strong><?php echo esc_html($evangelho['footer_response']); ?></strong></p>
            </div>
        <?php endif; ?>
    </div>

    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Article",
      "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "<?php echo esc_url(home_url(add_query_arg(null, null))); ?>"
      },
      "headline": "Liturgia do Dia - <?php echo esc_js($data_formatada); ?>",
      "datePublished": "<?php echo esc_js($data); ?>",
      "dateModified": "<?php echo esc_js($data); ?>",
      "author": {
        "@type": "Person",
        "name": "Josué Santos",
        "url": "https://josuesantos.github.io/"
      },
      "publisher": {
        "@type": "Organization",
        "name": "Eco da Palavra",
        "logo": {
          "@type": "ImageObject",
          "url": "https://ecodapalavra.com.br/logo.png"
        }
      },
      "articleSection": "Liturgia Diária",
      "keywords": "Liturgia diária, Bíblia, Evangelho, Salmo, Leitura do dia, Missa, Leituras católicas",
      "description": "Leituras bíblicas do dia <?php echo esc_js($data_formatada); ?>: Primeira Leitura, Salmo, Segunda Leitura e Evangelho.",
      "articleBody": "<?php echo esc_js(substr(strip_tags($primeira['text'] . ' ' . implode(' ', $salmo['content_psalm']) . ' ' . $segunda['text'] . ' ' . $evangelho['text']), 0, 1000)); ?>"
    }
    </script>
    <?php

    return ob_get_clean();
}
add_shortcode('liturgia_diaria', 'liturgia_diaria_shortcode');
