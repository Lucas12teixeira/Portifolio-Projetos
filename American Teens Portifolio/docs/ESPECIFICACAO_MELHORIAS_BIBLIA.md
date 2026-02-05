# 📖 Especificação Técnica: Melhorias no Sistema de Destaques da Bíblia

## 📋 Índice
- [Resumo Executivo](#resumo-executivo)
- [Análise do Sistema Atual](#análise-do-sistema-atual)
- [Melhoria 1: Persistência Visual de Destaques](#melhoria-1-persistência-visual-de-destaques)
- [Melhoria 2: Funcionalidade de Compartilhamento](#melhoria-2-funcionalidade-de-compartilhamento)
- [Implementação Detalhada](#implementação-detalhada)
- [Considerações de UX/UI](#considerações-de-uxui)
- [Performance e Otimização](#performance-e-otimização)
- [Testes e Validação](#testes-e-validação)

---

## Resumo Executivo

### Contexto
O aplicativo American Teens possui funcionalidade de leitura bíblica com sistema de destaques, mas com duas limitações importantes que afetam a experiência do usuário.

### Problemas Identificados

**Problema 1: Visibilidade Limitada dos Destaques**
- Destaques só aparecem durante a leitura do capítulo específico
- Usuários não conseguem ver destaques em buscas ou listas
- Falta de indicadores visuais em navegação

**Problema 2: Falta de Compartilhamento Rápido**
- Não há opção para compartilhar versículos destacados
- Menu de contexto possui apenas "Remover destaque"
- Usuários precisam copiar manualmente para compartilhar

### Objetivos das Melhorias

✅ Tornar destaques visíveis em todas as visualizações  
✅ Adicionar funcionalidade de compartilhamento  
✅ Melhorar acessibilidade dos versículos destacados  
✅ Manter performance e responsividade  
✅ Garantir compatibilidade com PWA offline  

---

## Análise do Sistema Atual

### Arquitetura de Destaques

**Armazenamento:**
```javascript
// localStorage: 'bible-highlights'
{
  "João:3:16": {
    "book": "João",
    "chapter": "3",
    "verse": "16",
    "color": "yellow",
    "text": "Porque Deus amou o mundo...",
    "date": "2026-02-04T10:30:00.000Z"
  }
}
```

**Renderização Atual:**
```javascript
// bible-reader.js - linha ~28
const highlight = highlights[key];
const highlightClass = highlight ? `highlighted highlight-${highlight.color}` : '';

<span class="verse-item ${highlightClass}" ...>
```

**Menu de Contexto Atual:**
```javascript
// bible-reader.js - showHighlightMenu()
// Opções existentes:
// - Escolher cores (amarelo, verde, azul, rosa, roxo)
// - Remover destaque
```

### Pontos de Integração Identificados

1. **BibleReader.renderContinuousReading()** - Renderização de capítulos
2. **Bible.searchVerses()** - Resultados de busca
3. **BibleReader.showHighlightMenu()** - Menu de contexto
4. **BibleReader.showHighlights()** - Tela de destaques salvos

---

## Melhoria 1: Persistência Visual de Destaques

### 1.1 Especificação Funcional

**Requisito:** Versículos destacados devem ser visualmente identificáveis em todas as visualizações do aplicativo.

**Contextos de Aplicação:**
- ✅ Leitura de capítulo (já implementado)
- ➕ Resultados de busca
- ➕ Lista de favoritos
- ➕ Versículo do dia
- ➕ Compartilhamentos
- ➕ Modal de destaques

### 1.2 Solução Técnica

#### A. Criar Função Universal de Destaque

**Arquivo:** `js/modules/bible-reader.js`

```javascript
/**
 * Aplica destaque visual a um elemento HTML de versículo
 * Função universal reutilizável em todas as views
 * @param {HTMLElement} verseElement - Elemento do versículo
 * @param {string} book - Nome do livro
 * @param {string} chapter - Número do capítulo
 * @param {string} verse - Número do versículo
 * @returns {Object|null} Dados do destaque se existir
 */
static applyHighlightToElement(verseElement, book, chapter, verse) {
  const highlights = BibleReader.getHighlights();
  const key = `${book}:${chapter}:${verse}`;
  const highlight = highlights[key];
  
  if (highlight && verseElement) {
    // Adicionar classes de destaque
    verseElement.classList.add('highlighted', `highlight-${highlight.color}`);
    
    // Adicionar atributos data para fácil identificação
    verseElement.dataset.highlighted = 'true';
    verseElement.dataset.highlightColor = highlight.color;
    verseElement.dataset.highlightDate = highlight.date;
    
    // Adicionar indicador visual (badge)
    const badge = document.createElement('span');
    badge.className = 'highlight-indicator';
    badge.innerHTML = '✨';
    badge.title = `Destacado em ${new Date(highlight.date).toLocaleDateString('pt-BR')}`;
    
    // Inserir badge antes do número do versículo
    const verseNumber = verseElement.querySelector('.verse-number');
    if (verseNumber && !verseElement.querySelector('.highlight-indicator')) {
      verseNumber.insertAdjacentElement('beforebegin', badge);
    }
    
    return highlight;
  }
  
  return null;
}

/**
 * Aplica destaques a múltiplos elementos de versículos
 * Útil para listas e resultados de busca
 * @param {NodeList|Array} verseElements - Lista de elementos
 */
static applyHighlightsToElements(verseElements) {
  verseElements.forEach(element => {
    const book = element.dataset.book;
    const chapter = element.dataset.chapter;
    const verse = element.dataset.verse;
    
    if (book && chapter && verse) {
      BibleReader.applyHighlightToElement(element, book, chapter, verse);
    }
  });
}
```

#### B. Atualizar Renderização de Busca

**Arquivo:** `js/modules/bible.js`

Modificar a função `searchVerses()` para incluir informação de destaques:

```javascript
static async searchVerses(query, maxResults = 50) {
  try {
    const bibleData = await Bible.loadBibleData();
    if (!bibleData) return [];

    const results = [];
    const queryLower = query.toLowerCase().trim();
    
    // Carregar destaques para marcar resultados
    const highlights = BibleReader.getHighlights();

    for (const bookData of bibleData.books) {
      for (const chapterData of bookData.chapters) {
        for (const verseData of chapterData.verses) {
          if (verseData.text.toLowerCase().includes(queryLower)) {
            const key = `${bookData.name}:${chapterData.number}:${verseData.number}`;
            
            results.push({
              id: `${bookData.name}-${chapterData.number}-${verseData.number}`,
              text: verseData.text,
              book: bookData.name,
              chapter: chapterData.number,
              verse: verseData.number,
              // NOVO: Incluir informação de destaque
              highlighted: !!highlights[key],
              highlightColor: highlights[key]?.color || null,
              highlightDate: highlights[key]?.date || null
            });

            if (results.length >= maxResults) {
              return results;
            }
          }
        }
      }
    }

    return results;

  } catch (error) {
    console.error("[Bible] Erro na busca:", error);
    return [];
  }
}
```

#### C. Atualizar Template de Resultados

**Arquivo:** `js/modules/bible.js` - Modificar `showSearchResults()`

```javascript
static showSearchResults(results, query) {
  if (results.length === 0) {
    return Components.createEmptyState(
      '🔍',
      'Nenhum resultado',
      `Não foram encontrados versículos com "${query}"`
    );
  }

  const resultsHtml = results.map(verse => {
    // Determinar classe de destaque
    const highlightClass = verse.highlighted 
      ? `search-result-highlighted highlight-${verse.highlightColor}` 
      : '';
    
    // Criar badge de destaque
    const highlightBadge = verse.highlighted 
      ? `<span class="verse-highlight-badge" style="color: var(--highlight-${verse.highlightColor})">✨</span>`
      : '';
    
    return `
      <div class="verse-result-card ${highlightClass}" 
           data-book="${verse.book}" 
           data-chapter="${verse.chapter}" 
           data-verse="${verse.verse}"
           onclick="Bible.loadChapter('${verse.book}', ${verse.chapter})">
        <div class="verse-result-header">
          ${highlightBadge}
          <strong class="verse-result-reference">
            ${verse.book} ${verse.chapter}:${verse.verse}
          </strong>
        </div>
        <p class="verse-result-text">${Components.highlightSearchText(verse.text, query)}</p>
        ${verse.highlighted ? `
          <div class="verse-result-meta">
            <span class="highlight-label" style="background: var(--highlight-${verse.highlightColor}-light)">
              Destacado
            </span>
          </div>
        ` : ''}
      </div>
    `;
  }).join('');

  return `
    <div class="search-results-container">
      <div class="search-results-header">
        <h3>📖 Resultados da Busca</h3>
        <p>${results.length} versículo${results.length !== 1 ? 's' : ''} encontrado${results.length !== 1 ? 's' : ''}</p>
      </div>
      <div class="search-results-list">
        ${resultsHtml}
      </div>
      
      <style>
        .search-result-highlighted {
          border-left: 4px solid;
          background: rgba(255, 255, 255, 0.05);
        }
        
        .search-result-highlighted.highlight-yellow {
          border-color: #ffc107;
          background: rgba(255, 193, 7, 0.1);
        }
        
        .search-result-highlighted.highlight-green {
          border-color: #28a745;
          background: rgba(40, 167, 69, 0.1);
        }
        
        .search-result-highlighted.highlight-blue {
          border-color: #17a2b8;
          background: rgba(23, 162, 184, 0.1);
        }
        
        .search-result-highlighted.highlight-pink {
          border-color: #e83e8c;
          background: rgba(232, 62, 140, 0.1);
        }
        
        .search-result-highlighted.highlight-purple {
          border-color: #7c3aed;
          background: rgba(124, 58, 237, 0.1);
        }
        
        .verse-highlight-badge {
          font-size: 18px;
          margin-right: 8px;
          animation: sparkle 2s infinite;
        }
        
        @keyframes sparkle {
          0%, 100% { opacity: 1; transform: scale(1); }
          50% { opacity: 0.7; transform: scale(1.1); }
        }
        
        .highlight-label {
          display: inline-block;
          padding: 4px 8px;
          border-radius: 4px;
          font-size: 11px;
          font-weight: 600;
          text-transform: uppercase;
          margin-top: 8px;
        }
      </style>
    </div>
  `;
}
```

#### D. Adicionar Indicadores na Navegação

**Arquivo:** `js/modules/bible.js` - Adicionar contador de destaques

```javascript
/**
 * Obter estatísticas de destaques para um capítulo
 * @param {string} book - Nome do livro
 * @param {number} chapter - Número do capítulo
 * @returns {Object} Estatísticas
 */
static getChapterHighlightStats(book, chapter) {
  const highlights = BibleReader.getHighlights();
  const chapterHighlights = Object.values(highlights).filter(
    h => h.book === book && parseInt(h.chapter) === chapter
  );
  
  return {
    count: chapterHighlights.length,
    hasHighlights: chapterHighlights.length > 0,
    colors: [...new Set(chapterHighlights.map(h => h.color))]
  };
}

/**
 * Renderizar seletor de capítulos com indicadores de destaques
 */
static showChapterSelector() {
  const booksData = Bible.getBooksData();
  
  const booksHtml = booksData.map(book => {
    // Contar destaques por livro
    const highlights = BibleReader.getHighlights();
    const bookHighlights = Object.values(highlights).filter(h => h.book === book.name);
    const highlightCount = bookHighlights.length;
    
    const highlightBadge = highlightCount > 0 
      ? `<span class="book-highlights-badge">✨ ${highlightCount}</span>`
      : '';
    
    return `
      <div class="book-card" onclick="Bible.showChaptersForBook('${book.name}')">
        <div class="book-card-header">
          <h4>${book.name}</h4>
          ${highlightBadge}
        </div>
        <p class="book-chapters">${book.chapters} capítulos</p>
      </div>
    `;
  }).join('');
  
  // ... resto do código
}
```

---

## Melhoria 2: Funcionalidade de Compartilhamento

### 2.1 Especificação Funcional

**Requisito:** Adicionar opção de compartilhar versículos destacados no menu de contexto.

**Formatos de Compartilhamento:**
1. **Texto Simples** - Para copiar/colar
2. **Imagem** - Card visual estilizado
3. **Link** - Para redes sociais
4. **Web Share API** - Compartilhamento nativo no mobile

### 2.2 Solução Técnica

#### A. Atualizar Menu de Contexto

**Arquivo:** `js/modules/bible-reader.js` - Modificar `showHighlightMenu()`

```javascript
static showHighlightMenu(verseElement, x, y) {
  // Remover menu anterior
  document.querySelector('.highlight-menu')?.remove();
  
  const book = verseElement.dataset.book;
  const chapter = verseElement.dataset.chapter;
  const verse = verseElement.dataset.verse;
  const verseText = verseElement.querySelector('.verse-text').textContent;
  
  // Verificar se já está destacado
  const highlights = BibleReader.getHighlights();
  const key = `${book}:${chapter}:${verse}`;
  const currentHighlight = highlights[key];
  
  const menu = document.createElement('div');
  menu.className = 'highlight-menu';
  menu.style.left = x + 'px';
  menu.style.top = y + 'px';
  
  // Ajustar posição se sair da tela
  setTimeout(() => {
    const rect = menu.getBoundingClientRect();
    if (rect.right > window.innerWidth) {
      menu.style.left = (window.innerWidth - rect.width - 10) + 'px';
    }
    if (rect.bottom > window.innerHeight) {
      menu.style.top = (y - rect.height - 10) + 'px';
    }
  }, 0);
  
  menu.innerHTML = `
    <div class="highlight-menu-header">
      <strong>${book} ${chapter}:${verse}</strong>
    </div>
    
    ${!currentHighlight ? `
      <div class="highlight-menu-section">
        <p class="highlight-menu-label">Escolha uma cor:</p>
        <div class="highlight-colors">
          <button class="color-btn yellow" onclick="BibleReader.highlightVerse('${book}', '${chapter}', '${verse}', 'yellow')" title="Amarelo">🟡</button>
          <button class="color-btn green" onclick="BibleReader.highlightVerse('${book}', '${chapter}', '${verse}', 'green')" title="Verde">🟢</button>
          <button class="color-btn blue" onclick="BibleReader.highlightVerse('${book}', '${chapter}', '${verse}', 'blue')" title="Azul">🔵</button>
          <button class="color-btn pink" onclick="BibleReader.highlightVerse('${book}', '${chapter}', '${verse}', 'pink')" title="Rosa">🔴</button>
          <button class="color-btn purple" onclick="BibleReader.highlightVerse('${book}', '${chapter}', '${verse}', 'purple')" title="Roxo">🟣</button>
        </div>
      </div>
    ` : ''}
    
    <!-- NOVA SEÇÃO: Compartilhar -->
    <div class="highlight-menu-section">
      <p class="highlight-menu-label">Compartilhar:</p>
      <div class="share-options">
        <button class="share-btn" onclick="BibleReader.shareVerse('${book}', '${chapter}', '${verse}', 'text')" title="Copiar Texto">
          📋 Copiar
        </button>
        <button class="share-btn" onclick="BibleReader.shareVerse('${book}', '${chapter}', '${verse}', 'image')" title="Compartilhar como Imagem">
          🖼️ Imagem
        </button>
        <button class="share-btn" onclick="BibleReader.shareVerse('${book}', '${chapter}', '${verse}', 'native')" title="Compartilhar">
          🔗 Enviar
        </button>
      </div>
    </div>
    
    ${currentHighlight ? `
      <button onclick="BibleReader.removeHighlight('${book}', '${chapter}', '${verse}')" class="remove-highlight-btn">
        🗑️ Remover Destaque
      </button>
    ` : ''}
    
    <style>
      .highlight-menu {
        position: fixed;
        background: white;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.2);
        padding: 16px;
        z-index: 10000;
        min-width: 280px;
        max-width: 320px;
        animation: menuSlideIn 0.2s ease;
      }
      
      @keyframes menuSlideIn {
        from {
          opacity: 0;
          transform: translateY(-10px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }
      
      .highlight-menu-header {
        padding-bottom: 12px;
        border-bottom: 1px solid #e0e0e0;
        margin-bottom: 12px;
        color: #333;
        font-size: 14px;
      }
      
      .highlight-menu-section {
        margin-bottom: 12px;
      }
      
      .highlight-menu-section:last-child {
        margin-bottom: 0;
      }
      
      .highlight-menu-label {
        font-size: 12px;
        color: #666;
        margin-bottom: 8px;
        font-weight: 600;
      }
      
      .highlight-colors {
        display: flex;
        gap: 8px;
        justify-content: space-between;
      }
      
      .color-btn {
        flex: 1;
        padding: 8px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        background: white;
        font-size: 20px;
        cursor: pointer;
        transition: all 0.2s ease;
      }
      
      .color-btn:hover {
        transform: scale(1.1);
        border-color: #333;
      }
      
      /* NOVOS ESTILOS: Botões de compartilhamento */
      .share-options {
        display: flex;
        flex-direction: column;
        gap: 8px;
      }
      
      .share-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 12px;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        color: #333;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: left;
      }
      
      .share-btn:hover {
        background: #e9ecef;
        transform: translateX(4px);
      }
      
      .share-btn:active {
        transform: translateX(2px);
      }
      
      .remove-highlight-btn {
        width: 100%;
        padding: 10px;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        color: #dc3545;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-top: 12px;
      }
      
      .remove-highlight-btn:hover {
        background: #dc3545;
        color: white;
      }
    </style>
  `;
  
  document.body.appendChild(menu);
  
  // Fechar ao clicar fora
  setTimeout(() => {
    document.addEventListener('click', function closeMenu(e) {
      if (!menu.contains(e.target) && e.target !== verseElement) {
        menu.remove();
        document.removeEventListener('click', closeMenu);
      }
    });
  }, 100);
}
```

#### B. Implementar Funções de Compartilhamento

**Arquivo:** `js/modules/bible-reader.js`

```javascript
/**
 * Compartilhar versículo em diferentes formatos
 * @param {string} book - Nome do livro
 * @param {string} chapter - Número do capítulo
 * @param {string} verse - Número do versículo
 * @param {string} format - Formato: 'text', 'image', 'native'
 */
static async shareVerse(book, chapter, verse, format) {
  // Obter texto do versículo
  const verseElement = document.querySelector(
    `[data-book="${book}"][data-chapter="${chapter}"][data-verse="${verse}"]`
  );
  const verseText = verseElement 
    ? verseElement.querySelector('.verse-text').textContent 
    : '';
  
  if (!verseText) {
    UI.showError('Não foi possível obter o texto do versículo');
    return;
  }
  
  // Referência formatada
  const reference = `${book} ${chapter}:${verse}`;
  
  // Fechar menu
  document.querySelector('.highlight-menu')?.remove();
  
  switch (format) {
    case 'text':
      await BibleReader.shareAsText(reference, verseText);
      break;
    case 'image':
      await BibleReader.shareAsImage(reference, verseText);
      break;
    case 'native':
      await BibleReader.shareNative(reference, verseText);
      break;
    default:
      UI.showError('Formato de compartilhamento inválido');
  }
}

/**
 * Compartilhar como texto (copiar para clipboard)
 */
static async shareAsText(reference, text) {
  const formattedText = `"${text}"\n\n— ${reference}`;
  
  try {
    // Usar Clipboard API moderna
    if (navigator.clipboard && navigator.clipboard.writeText) {
      await navigator.clipboard.writeText(formattedText);
      UI.showSuccess('✅ Versículo copiado para a área de transferência!');
    } else {
      // Fallback para navegadores antigos
      const textarea = document.createElement('textarea');
      textarea.value = formattedText;
      textarea.style.position = 'fixed';
      textarea.style.opacity = '0';
      document.body.appendChild(textarea);
      textarea.select();
      document.execCommand('copy');
      document.body.removeChild(textarea);
      UI.showSuccess('✅ Versículo copiado!');
    }
  } catch (error) {
    console.error('Erro ao copiar:', error);
    UI.showError('Não foi possível copiar o versículo');
  }
}

/**
 * Compartilhar como imagem (gerar card visual)
 */
static async shareAsImage(reference, text) {
  // Obter informações do destaque (se existir)
  const highlights = BibleReader.getHighlights();
  const [book, chapterVerse] = reference.split(' ');
  const [chapter, verse] = chapterVerse.split(':');
  const key = `${book} ${chapter}:${verse}`;
  const highlight = highlights[key];
  
  // Cores do tema
  const colors = {
    yellow: { bg: '#fff3cd', border: '#ffc107', text: '#856404' },
    green: { bg: '#d4edda', border: '#28a745', text: '#155724' },
    blue: { bg: '#d1ecf1', border: '#17a2b8', text: '#0c5460' },
    pink: { bg: '#f8d7da', border: '#e83e8c', text: '#721c24' },
    purple: { bg: '#e7d6f5', border: '#7c3aed', text: '#4c1d95' },
    default: { bg: '#e9ecef', border: '#7c3aed', text: '#212529' }
  };
  
  const color = highlight ? colors[highlight.color] : colors.default;
  
  // Criar modal de preview da imagem
  const modal = document.createElement('div');
  modal.className = 'verse-image-modal';
  modal.onclick = (e) => {
    if (e.target === modal) modal.remove();
  };
  
  modal.innerHTML = `
    <div class="verse-image-modal-content">
      <div class="verse-image-preview">
        <div class="verse-card-image" id="verse-card-render" style="
          background: linear-gradient(135deg, ${color.bg} 0%, white 100%);
          border: 4px solid ${color.border};
          padding: 40px;
          border-radius: 20px;
          max-width: 600px;
          box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        ">
          <div style="
            font-size: 60px;
            margin-bottom: 20px;
            opacity: 0.3;
          ">📖</div>
          <p style="
            font-size: 24px;
            line-height: 1.6;
            color: ${color.text};
            margin-bottom: 30px;
            font-family: 'Georgia', serif;
          ">"${text}"</p>
          <div style="
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            color: ${color.border};
          ">— ${reference}</div>
          <div style="
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid ${color.border};
            text-align: center;
            color: ${color.text};
            opacity: 0.7;
            font-size: 14px;
          ">American Teens 🙏</div>
        </div>
      </div>
      
      <div class="verse-image-actions">
        <button onclick="this.closest('.verse-image-modal').remove()" class="btn-secondary">
          Cancelar
        </button>
        <button onclick="BibleReader.downloadVerseImage('verse-card-render', '${reference}')" class="btn-primary">
          📥 Baixar Imagem
        </button>
      </div>
    </div>
    
    <style>
      .verse-image-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.8);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        animation: fadeIn 0.3s ease;
      }
      
      .verse-image-modal-content {
        background: white;
        border-radius: 16px;
        padding: 30px;
        max-width: 800px;
        max-height: 90vh;
        overflow-y: auto;
      }
      
      .verse-image-preview {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
      }
      
      .verse-image-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
      }
      
      .btn-primary, .btn-secondary {
        padding: 12px 24px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
      }
      
      .btn-primary {
        background: #7c3aed;
        color: white;
      }
      
      .btn-primary:hover {
        background: #6d28d9;
        transform: translateY(-2px);
      }
      
      .btn-secondary {
        background: #e9ecef;
        color: #495057;
      }
      
      .btn-secondary:hover {
        background: #dee2e6;
      }
    </style>
  `;
  
  document.body.appendChild(modal);
}

/**
 * Baixar imagem do versículo
 */
static async downloadVerseImage(elementId, filename) {
  try {
    // Importar biblioteca html2canvas dinamicamente
    if (!window.html2canvas) {
      UI.showInfo('⏳ Carregando biblioteca de imagens...');
      
      const script = document.createElement('script');
      script.src = 'https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js';
      script.async = true;
      
      await new Promise((resolve, reject) => {
        script.onload = resolve;
        script.onerror = reject;
        document.head.appendChild(script);
      });
    }
    
    const element = document.getElementById(elementId);
    if (!element) {
      throw new Error('Elemento não encontrado');
    }
    
    UI.showInfo('📸 Gerando imagem...');
    
    // Gerar canvas da imagem
    const canvas = await html2canvas(element, {
      backgroundColor: null,
      scale: 2, // Maior qualidade
      logging: false
    });
    
    // Converter para blob e baixar
    canvas.toBlob((blob) => {
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = `${filename.replace(/[^a-z0-9]/gi, '_')}.png`;
      a.click();
      URL.revokeObjectURL(url);
      
      UI.showSuccess('✅ Imagem baixada com sucesso!');
      document.querySelector('.verse-image-modal')?.remove();
    }, 'image/png');
    
  } catch (error) {
    console.error('Erro ao gerar imagem:', error);
    UI.showError('Não foi possível gerar a imagem');
  }
}

/**
 * Compartilhar usando Web Share API nativa
 * Funciona em dispositivos móveis e PWA
 */
static async shareNative(reference, text) {
  const shareData = {
    title: reference,
    text: `"${text}"\n\n— ${reference}`,
    url: window.location.origin
  };
  
  try {
    // Verificar se Web Share API está disponível
    if (navigator.share) {
      await navigator.share(shareData);
      UI.showSuccess('✅ Versículo compartilhado!');
    } else {
      // Fallback: copiar para clipboard
      UI.showInfo('💡 Compartilhamento nativo não disponível. Texto copiado!');
      await BibleReader.shareAsText(reference, text);
    }
  } catch (error) {
    if (error.name === 'AbortError') {
      // Usuário cancelou o compartilhamento
      return;
    }
    console.error('Erro ao compartilhar:', error);
    // Fallback: copiar para clipboard
    await BibleReader.shareAsText(reference, text);
  }
}
```

---

## Implementação Detalhada

### Passo 1: Backup e Preparação

```bash
# Fazer backup dos arquivos que serão modificados
cp js/modules/bible-reader.js js/modules/bible-reader.js.backup
cp js/modules/bible.js js/modules/bible.js.backup

# Criar branch para desenvolvimento
git checkout -b feature/bible-highlights-improvements
```

### Passo 2: Implementar Persistência Visual

1. **Adicionar funções universais de destaque** em `bible-reader.js`
2. **Atualizar função de busca** em `bible.js` para incluir info de destaques
3. **Modificar templates HTML** para mostrar badges e indicadores
4. **Adicionar estilos CSS** para novos componentes visuais

### Passo 3: Implementar Compartilhamento

1. **Atualizar menu de contexto** com opções de compartilhamento
2. **Implementar função `shareVerse()`** com três formatos
3. **Adicionar modal de preview** para compartilhamento como imagem
4. **Integrar Web Share API** para compartilhamento nativo

### Passo 4: Testes

```javascript
// Arquivo: tests/bible-highlights.test.js

describe('Bible Highlights System', () => {
  describe('Visual Persistence', () => {
    it('should show highlights in search results', async () => {
      // Destacar versículo
      BibleReader.highlightVerse('João', '3', '16', 'yellow');
      
      // Buscar versículo
      const results = await Bible.searchVerses('Deus amou');
      
      // Verificar que resultado está marcado como destacado
      const highlightedResult = results.find(
        r => r.book === 'João' && r.chapter === 3 && r.verse === 16
      );
      
      expect(highlightedResult.highlighted).toBe(true);
      expect(highlightedResult.highlightColor).toBe('yellow');
    });
    
    it('should apply highlights to all verse elements', () => {
      // Criar elementos de versículos
      const container = document.createElement('div');
      container.innerHTML = `
        <span class="verse-item" data-book="João" data-chapter="3" data-verse="16">...</span>
        <span class="verse-item" data-book="João" data-chapter="3" data-verse="17">...</span>
      `;
      
      // Destacar versículo 16
      BibleReader.highlightVerse('João', '3', '16', 'blue');
      
      // Aplicar destaques
      const elements = container.querySelectorAll('.verse-item');
      BibleReader.applyHighlightsToElements(elements);
      
      // Verificar aplicação
      expect(elements[0].classList.contains('highlighted')).toBe(true);
      expect(elements[0].classList.contains('highlight-blue')).toBe(true);
      expect(elements[1].classList.contains('highlighted')).toBe(false);
    });
  });
  
  describe('Share Functionality', () => {
    it('should copy verse text to clipboard', async () => {
      // Mock clipboard API
      navigator.clipboard = {
        writeText: jest.fn().mockResolvedValue()
      };
      
      await BibleReader.shareAsText('João 3:16', 'Porque Deus amou o mundo...');
      
      expect(navigator.clipboard.writeText).toHaveBeenCalledWith(
        expect.stringContaining('João 3:16')
      );
    });
    
    it('should use Web Share API when available', async () => {
      // Mock Web Share API
      navigator.share = jest.fn().mockResolvedValue();
      
      await BibleReader.shareNative('João 3:16', 'Porque Deus amou o mundo...');
      
      expect(navigator.share).toHaveBeenCalledWith({
        title: 'João 3:16',
        text: expect.stringContaining('Porque Deus amou o mundo'),
        url: expect.any(String)
      });
    });
  });
});
```

---

## Considerações de UX/UI

### Princípios de Design

1. **Visibilidade Progressiva**
   - Destaques não devem sobrecarregar a interface
   - Usar animações sutis (sparkle effect)
   - Badges pequenos e não intrusivos

2. **Feedback Imediato**
   - Toasts de confirmação para todas as ações
   - Animações de sucesso/erro
   - Loading states para operações assíncronas

3. **Consistência Visual**
   - Mesmas cores de destaque em todos os contextos
   - Estilo unificado de badges e indicadores
   - Transições suaves entre estados

### Acessibilidade

```css
/* Adicionar suporte para leitores de tela */
.highlight-indicator[aria-label] {
  position: relative;
}

.highlight-indicator::after {
  content: attr(aria-label);
  position: absolute;
  left: 100%;
  top: 50%;
  transform: translateY(-50%);
  background: rgba(0,0,0,0.8);
  color: white;
  padding: 4px 8px;
  border-radius: 4px;
  font-size: 12px;
  white-space: nowrap;
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.2s ease;
}

.highlight-indicator:hover::after,
.highlight-indicator:focus::after {
  opacity: 1;
}

/* Alto contraste para deficiência visual */
@media (prefers-contrast: high) {
  .verse-item.highlighted {
    border-width: 4px;
    border-style: solid;
  }
}

/* Redução de movimento */
@media (prefers-reduced-motion: reduce) {
  .verse-highlight-badge {
    animation: none !important;
  }
  
  .highlight-menu {
    animation: none !important;
  }
}
```

### Responsividade Mobile

```css
@media (max-width: 768px) {
  .highlight-menu {
    position: fixed;
    left: 10px !important;
    right: 10px !important;
    top: auto !important;
    bottom: 20px;
    min-width: auto;
    max-width: none;
  }
  
  .share-options {
    flex-direction: row;
    overflow-x: auto;
  }
  
  .share-btn {
    flex: 1;
    min-width: 100px;
  }
  
  .verse-image-modal-content {
    padding: 15px;
  }
  
  .verse-card-image {
    padding: 20px !important;
    font-size: 18px !important;
  }
}
```

---

## Performance e Otimização

### 1. Cache e Memoização

```javascript
// Cache de destaques processados
class BibleHighlightCache {
  static cache = new Map();
  static maxSize = 100;
  
  static get(key) {
    return this.cache.get(key);
  }
  
  static set(key, value) {
    if (this.cache.size >= this.maxSize) {
      // Remove item mais antigo (FIFO)
      const firstKey = this.cache.keys().next().value;
      this.cache.delete(firstKey);
    }
    this.cache.set(key, value);
  }
  
  static clear() {
    this.cache.clear();
  }
}

// Usar cache na busca
static async searchVerses(query, maxResults = 50) {
  const cacheKey = `search_${query}_${maxResults}`;
  const cached = BibleHighlightCache.get(cacheKey);
  
  if (cached) {
    return cached;
  }
  
  const results = await this.performSearch(query, maxResults);
  BibleHighlightCache.set(cacheKey, results);
  
  return results;
}
```

### 2. Lazy Loading de Imagens

```javascript
// Carregar html2canvas apenas quando necessário
static async ensureHtml2Canvas() {
  if (window.html2canvas) {
    return window.html2canvas;
  }
  
  return new Promise((resolve, reject) => {
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js';
    script.async = true;
    script.onload = () => resolve(window.html2canvas);
    script.onerror = reject;
    document.head.appendChild(script);
  });
}
```

### 3. Debounce de Busca

```javascript
// Debounce para evitar buscas excessivas
static debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

// Aplicar na busca
static searchWithDebounce = BibleReader.debounce(
  (query) => Bible.performSearch(query),
  300
);
```

### 4. Virtualização para Grandes Listas

```javascript
// Para listas grandes de destaques, usar virtualização
static renderVirtualizedHighlights(highlights, containerHeight = 600) {
  const itemHeight = 120;
  const visibleCount = Math.ceil(containerHeight / itemHeight);
  let scrollTop = 0;
  
  const container = document.createElement('div');
  container.style.height = containerHeight + 'px';
  container.style.overflow = 'auto';
  container.style.position = 'relative';
  
  const content = document.createElement('div');
  content.style.height = (highlights.length * itemHeight) + 'px';
  content.style.position = 'relative';
  
  function render() {
    const startIndex = Math.floor(scrollTop / itemHeight);
    const endIndex = Math.min(
      startIndex + visibleCount + 1,
      highlights.length
    );
    
    content.innerHTML = '';
    
    for (let i = startIndex; i < endIndex; i++) {
      const item = highlights[i];
      const el = document.createElement('div');
      el.style.position = 'absolute';
      el.style.top = (i * itemHeight) + 'px';
      el.style.height = itemHeight + 'px';
      el.innerHTML = BibleReader.renderHighlightCard(item);
      content.appendChild(el);
    }
  }
  
  container.addEventListener('scroll', () => {
    scrollTop = container.scrollTop;
    render();
  });
  
  container.appendChild(content);
  render();
  
  return container;
}
```

---

## Testes e Validação

### Checklist de Testes

#### Funcionalidade de Destaques

- [ ] Destaques aparecem corretamente durante leitura de capítulo
- [ ] Destaques aparecem em resultados de busca
- [ ] Badge de destaque (✨) é exibido corretamente
- [ ] Cores de destaque são aplicadas consistentemente
- [ ] Contador de destaques por livro funciona
- [ ] Remoção de destaque atualiza todas as views
- [ ] localStorage persiste destaques corretamente

#### Funcionalidade de Compartilhamento

- [ ] Botão "Copiar" copia texto formatado
- [ ] Botão "Imagem" gera preview correto
- [ ] Download de imagem funciona
- [ ] Web Share API funciona em mobile
- [ ] Fallback para clipboard funciona em desktop
- [ ] Compartilhamento cancela sem erros
- [ ] Texto compartilhado inclui referência

#### Performance

- [ ] Busca com 1000+ versículos é rápida (<500ms)
- [ ] Aplicação de destaques não causa lag
- [ ] Menu de contexto aparece instantaneamente
- [ ] Geração de imagem completa em <3s
- [ ] Cache de busca funciona corretamente
- [ ] Sem memory leaks após uso prolongado

#### Responsividade

- [ ] Menu de contexto se ajusta em mobile
- [ ] Botões de compartilhamento são tocáveis (>44px)
- [ ] Preview de imagem cabe na tela mobile
- [ ] Badges não quebram layout em telas pequenas
- [ ] Scrolling funciona suavemente

#### Acessibilidade

- [ ] Leitores de tela anunciam destaques
- [ ] Navegação por teclado funciona
- [ ] Contraste de cores é adequado (WCAG AA)
- [ ] Animações respeitam prefers-reduced-motion
- [ ] Tooltips são informativos

#### Compatibilidade

- [ ] Chrome 90+ (desktop e mobile)
- [ ] Firefox 88+ (desktop e mobile)
- [ ] Safari 14+ (desktop e mobile)
- [ ] Edge 90+
- [ ] PWA instalado funciona offline
- [ ] Service Worker mantém destaques offline

---

## Cronograma de Implementação

### Fase 1: Preparação (1 dia)
- ✅ Análise de código existente
- ✅ Criação de especificação técnica
- ✅ Setup de ambiente de desenvolvimento
- ✅ Backup de arquivos

### Fase 2: Persistência Visual (2-3 dias)
- Implementar função `applyHighlightToElement()`
- Atualizar função de busca
- Modificar templates de resultados
- Adicionar indicadores de navegação
- Testes unitários

### Fase 3: Compartilhamento (2-3 dias)
- Atualizar menu de contexto
- Implementar `shareVerse()` e formatos
- Criar modal de preview de imagem
- Integrar Web Share API
- Testes de integração

### Fase 4: Polimento (1-2 dias)
- Otimizações de performance
- Melhorias de UX/UI
- Ajustes de responsividade
- Testes de acessibilidade

### Fase 5: Deploy (1 dia)
- Testes em produção
- Documentação de usuário
- Release notes
- Monitoramento pós-deploy

**Total Estimado: 7-10 dias**

---

## Métricas de Sucesso

### KPIs

1. **Uso de Destaques**
   - Aumento de 50% em versículos destacados
   - 80%+ dos usuários ativos usam destaques
   - Média de 10+ destaques por usuário

2. **Compartilhamentos**
   - 5+ compartilhamentos por usuário/mês
   - 70% taxa de sucesso de compartilhamentos
   - 40% usam compartilhamento como imagem

3. **Performance**
   - Tempo de busca <500ms (95th percentile)
   - Menu de contexto <100ms
   - Geração de imagem <3s

4. **Satisfação**
   - NPS >50
   - 90%+ acham funcionalidade útil
   - <1% relatam bugs

---

## Documentação para Usuários

### Guia Rápido

**Como Destacar um Versículo:**
1. Toque ou clique em um versículo
2. Escolha uma cor de destaque
3. O versículo ficará destacado em todas as views

**Como Compartilhar:**
1. Toque em um versículo destacado
2. Escolha "Compartilhar"
3. Selecione o formato:
   - **Copiar**: Copia texto formatado
   - **Imagem**: Gera card visual
   - **Enviar**: Compartilha via apps

**Onde Ver Destaques:**
- ✅ Durante leitura de capítulos
- ✅ Em resultados de busca
- ✅ Na tela "Meus Destaques" (botão ✨)
- ✅ No seletor de livros (contador)

---

## Conclusão

Esta especificação técnica detalha as melhorias necessárias para transformar o sistema de destaques da Bíblia em uma funcionalidade completa e intuitiva. As implementações propostas:

✅ Tornam destaques visíveis em todas as views  
✅ Adicionam compartilhamento fácil e versátil  
✅ Mantêm performance e responsividade  
✅ Seguem boas práticas de UX e acessibilidade  
✅ São compatíveis com PWA offline  

Com essas melhorias, os usuários terão uma experiência significativamente melhor ao interagir com versículos bíblicos, aumentando o engajamento e a utilidade do aplicativo.

---

<div align="center">

**Especificação criada em: 4 de Fevereiro de 2026**

**Versão: 1.0**

Para dúvidas ou sugestões, entre em contato com a equipe de desenvolvimento.

</div>
