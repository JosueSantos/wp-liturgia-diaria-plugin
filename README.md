# 📖 WP Liturgia Diária CNBB

![WordPress Plugin](https://img.shields.io/badge/WordPress-Liturgia%20Di%C3%A1ria%20CNBB-21759B?logo=wordpress&logoColor=white)
![License](https://img.shields.io/badge/License-GPLv2-blue.svg)
![GitHub Repo stars](https://img.shields.io/github/stars/JosueSantos/wp-liturgia-diaria-plugin?style=social)

Plugin WordPress para exibir a **Liturgia Diária** (leituras da missa: primeira leitura, salmo, segunda leitura e evangelho) diretamente no seu site, consumindo dados oficiais da CNBB.

---

## ✨ Funcionalidades

* Exibe **Primeira Leitura, Salmo, Segunda Leitura e Evangelho**.
* Estilo moderno e responsivo com HTML + CSS próprio (prefixado para evitar conflitos).
* Shortcode simples para incluir em qualquer página ou post.
* Código aberto e disponível para contribuição.

![Print](/ecodapalavra.com.br_liturgia-diaria__dia%3D2025-08-17.png)

---

## 🚀 Instalação

1. Baixe este repositório ou clone no diretório de plugins do WordPress:

   ```bash
   cd wp-content/plugins/
   git clone https://github.com/JosueSantos/wp-liturgia-diaria-plugin.git
   ```

2. Ative o plugin no painel do WordPress em **Plugins → Ativar**.

3. Use o shortcode `[liturgia_diaria]` para exibir a liturgia na sua página ou post.

4. Use o shortcode `[liturgia_diaria_cnbb]` para exibir a liturgia utilizando outra fonte de dados na sua página ou post.

---

## 🔹 Publicar no seu site

Você pode apenas compactar a pasta `wp-liturgia-diaria-plugin/` em `.zip` e enviar para outros instalarem via **Plugins → Adicionar novo → Enviar plugin**.

---

## 🖼 Exemplo de uso

```html
[liturgia_diaria]
```

O shortcode renderiza automaticamente o conteúdo litúrgico do dia com formatação semelhante ao portal da CNBB.

---

## 👨‍💻 Autor

Desenvolvido por [Josué Santos](https://github.com/JosueSantos)
Missionário digital católico ✝️

---

## 📜 Licença

Este plugin é distribuído sob a licença **GPLv2 ou posterior**.
Mais detalhes: [GNU General Public License](https://www.gnu.org/licenses/gpl-2.0.html).
