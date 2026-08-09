(function(wp){
  var el = wp.element.createElement;
  var Inspector = wp.blockEditor.InspectorControls;
  var PanelBody = wp.components.PanelBody;
  var TextControl = wp.components.TextControl;

  // Post Hero
  wp.blocks.registerBlockType('prism/post-hero', {
    title: 'Post Hero',
    icon: 'cover-image',
    category: 'prism',
    attributes: { postsPerPage: { type: 'number', default: 1 }, offset: { type: 'number', default: 0 }, category: { type: 'number', default: 0 } },
    edit: function(props) {
      return [
        el(Inspector, {}, el(PanelBody, { title: 'Réglages' },
          el(TextControl, { label: 'Nombre', type: 'number', value: props.attributes.postsPerPage, onChange: function(v) { props.setAttributes({ postsPerPage: parseInt(v) || 1 }) } }),
          el(TextControl, { label: 'Offset', type: 'number', value: props.attributes.offset, onChange: function(v) { props.setAttributes({ offset: parseInt(v) || 0 }) } }),
          el(TextControl, { label: 'Catégorie (ID)', type: 'number', value: props.attributes.category, onChange: function(v) { props.setAttributes({ category: parseInt(v) || 0 }) } })
        )),
        el('div', { className: 'prism-block-preview' }, 'Post Hero — ' + props.attributes.postsPerPage + ' article(s)')
      ];
    },
    save: function() { return null; }
  });

  // Post Grid
  wp.blocks.registerBlockType('prism/post-grid', {
    title: 'Post Grid',
    icon: 'grid-view',
    category: 'prism',
    attributes: { postsPerPage: { type: 'number', default: 6 }, offset: { type: 'number', default: 0 }, columns: { type: 'number', default: 3 }, category: { type: 'number', default: 0 } },
    edit: function(props) {
      return [
        el(Inspector, {}, el(PanelBody, { title: 'Réglages' },
          el(TextControl, { label: 'Nombre', type: 'number', value: props.attributes.postsPerPage, onChange: function(v) { props.setAttributes({ postsPerPage: parseInt(v) || 6 }) } }),
          el(TextControl, { label: 'Colonnes', type: 'number', value: props.attributes.columns, onChange: function(v) { props.setAttributes({ columns: parseInt(v) || 3 }) } }),
          el(TextControl, { label: 'Offset', type: 'number', value: props.attributes.offset, onChange: function(v) { props.setAttributes({ offset: parseInt(v) || 0 }) } })
        )),
        el('div', { className: 'prism-block-preview' }, 'Post Grid — ' + props.attributes.columns + ' col × ' + props.attributes.postsPerPage + ' articles')
      ];
    },
    save: function() { return null; }
  });

  // Post Card
  wp.blocks.registerBlockType('prism/post-card', {
    title: 'Post Card',
    icon: 'admin-post',
    category: 'prism',
    attributes: { postId: { type: 'number', default: 0 } },
    edit: function(props) {
      return [
        el(Inspector, {}, el(PanelBody, { title: 'Article' },
          el(TextControl, { label: 'ID de l\'article', type: 'number', value: props.attributes.postId, onChange: function(v) { props.setAttributes({ postId: parseInt(v) || 0 }) } })
        )),
        el('div', { className: 'prism-block-preview' }, 'Post Card — ID: ' + (props.attributes.postId || '?'))
      ];
    },
    save: function() { return null; }
  });

  // Category Section
  wp.blocks.registerBlockType('prism/category-section', {
    title: 'Category Section', icon: 'category', category: 'prism',
    attributes: { title: { type: 'string', default: '' }, category: { type: 'number', default: 0 }, postsPerPage: { type: 'number', default: 5 }, accentColor: { type: 'string', default: '' } },
    edit: function(props) {
      return [
        el(Inspector, {}, el(PanelBody, { title: 'Catégorie' },
          el(TextControl, { label: 'Titre', value: props.attributes.title, onChange: function(v) { props.setAttributes({ title: v }) } }),
          el(TextControl, { label: 'ID Catégorie', type: 'number', value: props.attributes.category, onChange: function(v) { props.setAttributes({ category: parseInt(v) || 0 }) } }),
          el(TextControl, { label: 'Nombre', type: 'number', value: props.attributes.postsPerPage, onChange: function(v) { props.setAttributes({ postsPerPage: parseInt(v) || 5 }) } }),
          el(TextControl, { label: 'Couleur accent', value: props.attributes.accentColor, onChange: function(v) { props.setAttributes({ accentColor: v }) } })
        )),
        el('div', { className: 'prism-block-preview' }, 'Category Section — ' + (props.attributes.title || 'Catégorie ' + props.attributes.category) + ' (' + props.attributes.postsPerPage + ' articles)')
      ];
    },
    save: function() { return null; }
  });

  // Featured Posts
  wp.blocks.registerBlockType('prism/featured-posts', {
    title: 'Featured Posts', icon: 'star-filled', category: 'prism',
    attributes: { title: { type: 'string', default: 'À la une' }, postsPerPage: { type: 'number', default: 5 }, category: { type: 'number', default: 0 } },
    edit: function(props) {
      return [
        el(Inspector, {}, el(PanelBody, { title: 'Réglages' },
          el(TextControl, { label: 'Titre', value: props.attributes.title, onChange: function(v) { props.setAttributes({ title: v }) } }),
          el(TextControl, { label: 'Nombre', type: 'number', value: props.attributes.postsPerPage, onChange: function(v) { props.setAttributes({ postsPerPage: parseInt(v) || 5 }) } }),
          el(TextControl, { label: 'Catégorie (ID)', type: 'number', value: props.attributes.category, onChange: function(v) { props.setAttributes({ category: parseInt(v) || 0 }) } })
        )),
        el('div', { className: 'prism-block-preview' }, 'Featured Posts — ' + props.attributes.title + ' (' + props.attributes.postsPerPage + ' articles)')
      ];
    },
    save: function() { return null; }
  });

  // Magazine Headline
  wp.blocks.registerBlockType('prism/magazine-headline', {
    title: 'Magazine Headline', icon: 'align-pull-left', category: 'prism',
    attributes: {},
    edit: function() {
      return el('div', { className: 'prism-block-preview' }, 'Magazine Headline — 1 article hero + 4 en grille (auto)');
    },
    save: function() { return null; }
  });
})(window.wp);
