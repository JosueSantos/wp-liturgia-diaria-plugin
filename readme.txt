=== Liturgia Diária CNBB ===
Contributors: JosueSantos
Tags: liturgia, bíblia, cnbb, missa, evangelho, salmo
Requires at least: 5.8
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

![WordPress Plugin](https://img.shields.io/badge/WordPress-Liturgia%20Di%C3%A1ria%20CNBB-21759B?logo=wordpress&logoColor=white)
![License](https://img.shields.io/badge/License-GPLv2-blue.svg)
![GitHub Repo stars](https://img.shields.io/github/stars/JosueSantos/wp-liturgia-diaria-plugin?style=social)

Exibe a liturgia diária (leituras bíblicas da missa: primeira leitura, salmo, segunda leitura e evangelho) diretamente em seu site WordPress.

== Description ==
Este plugin adiciona dois shortcodes para exibir as leituras da liturgia diária:
- `[liturgia_diaria_cnbb]` (dados da API `railway.app`)
- `[liturgia_diaria]` (dados da API `vercel.app`)

== Installation ==
1. Faça o upload da pasta `liturgia-diaria-cnbb` para o diretório `/wp-content/plugins/`.
2. Ative o plugin no painel do WordPress em "Plugins".
3. Use o shortcode `[liturgia_diaria]` ou `[liturgia_diaria_cnbb]` em uma página ou post.

== Frequently Asked Questions ==

= Qual shortcode usar? =
- Use `[liturgia_diaria]` para dados mais completos (via API Vercel).
- Use `[liturgia_diaria_cnbb]` para dados simplificados (via API Railway).

== Author ==
Desenvolvido por [Josué Santos](https://github.com/JosueSantos)
Missionário Digital Católico ✝️

== Changelog ==

= 1.0.1 =
* Realizar a busca ao escolher o dia no calendario.

= 1.0.0 =
* Versão inicial do plugin.