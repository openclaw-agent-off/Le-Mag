(function(wp){
  var el = wp.element.createElement;
  var Inspector = wp.blockEditor.InspectorControls;
  var useBlockProps = wp.blockEditor.useBlockProps;
  var PanelBody = wp.components.PanelBody;
  var TextControl = wp.components.TextControl;
  var SelectControl = wp.components.SelectControl;

  function NumberControl(props) {
    return el(TextControl, { type: 'number', label: props.label, value: props.value, onChange: function(v) { props.onChange(parseInt(v) || props.min || 0) } });
  }

  // Post Hero
  wp.blocks.registerBlockType('prism/post-hero', {
    apiVersion: 3,
    title: 'Post Hero',
    icon: 'cover-image',
    category: 'prism',
    attributes: {
      postsPerPage: { type: 'number', default: 1 },
      offset: { type: 'number', default: 0 },
      category: { type: 'number', default: 0 }
    },
    edit: function(props) {
      var blockProps = useBlockProps();
      return el('div', blockProps,
        el(Inspector, {}, el(PanelBody, { title: 'Réglages' },
          el(NumberControl, { label: 'Articles', value: props.attributes.postsPerPage, min: 1, onChange: function(v) { props.setAttributes({ postsPerPage: v }) } }),
          el(NumberControl, { label: 'Offset', value: props.attributes.offset, min: 0, onChange: function(v) { props.setAttributes({ offset: v }) } }),
          el(NumberControl, { label: 'Catégorie (ID)', value: props.attributes.category, min: 0, onChange: function(v) { props.setAttributes({ category: v }) } })
        )),
        el('div', { className: 'prism-block-preview' }, 'Post Hero — ' + props.attributes.postsPerPage + ' article(s)')
      );
    },
    save: function() { return null; }
  });

  // Post Grid
  wp.blocks.registerBlockType('prism/post-grid', {
    apiVersion: 3,
    title: 'Post Grid',
    icon: 'grid-view',
    category: 'prism',
    attributes: {
      postsPerPage: { type: 'number', default: 6 },
      offset: { type: 'number', default: 0 },
      columns: { type: 'number', default: 3 },
      category: { type: 'number', default: 0 }
    },
    edit: function(props) {
      var blockProps = useBlockProps();
      return el('div', blockProps,
        el(Inspector, {}, el(PanelBody, { title: 'Disposition' },
          el(NumberControl, { label: 'Articles', value: props.attributes.postsPerPage, min: 1, onChange: function(v) { props.setAttributes({ postsPerPage: v }) } }),
          el(NumberControl, { label: 'Colonnes', value: props.attributes.columns, min: 1, onChange: function(v) { props.setAttributes({ columns: v }) } }),
          el(NumberControl, { label: 'Offset', value: props.attributes.offset, min: 0, onChange: function(v) { props.setAttributes({ offset: v }) } }),
          el(NumberControl, { label: 'Catégorie (ID)', value: props.attributes.category, min: 0, onChange: function(v) { props.setAttributes({ category: v }) } })
        )),
        el('div', { className: 'prism-block-preview' }, 'Post Grid — ' + props.attributes.columns + ' col × ' + props.attributes.postsPerPage + ' articles')
      );
    },
    save: function() { return null; }
  });

  // Post Card
  wp.blocks.registerBlockType('prism/post-card', {
    apiVersion: 3,
    title: 'Post Card',
    icon: 'admin-post',
    category: 'prism',
    attributes: {
      postId: { type: 'number', default: 0 }
    },
    edit: function(props) {
      var blockProps = useBlockProps();
      return el('div', blockProps,
        el(Inspector, {}, el(PanelBody, { title: 'Article' },
          el(NumberControl, { label: 'ID article', value: props.attributes.postId, min: 0, onChange: function(v) { props.setAttributes({ postId: v }) } })
        )),
        el('div', { className: 'prism-block-preview' }, 'Post Card — ID: ' + (props.attributes.postId || '?'))
      );
    },
    save: function() { return null; }
  });

  // Category Section
  wp.blocks.registerBlockType('prism/category-section', {
    apiVersion: 3,
    title: 'Category Section',
    icon: 'category',
    category: 'prism',
    attributes: {
      title: { type: 'string', default: '' },
      category: { type: 'number', default: 0 },
      postsPerPage: { type: 'number', default: 5 },
      accentColor: { type: 'string', default: '' }
    },
    edit: function(props) {
      var blockProps = useBlockProps();
      return el('div', blockProps,
        el(Inspector, {}, el(PanelBody, { title: 'Catégorie' },
          el(TextControl, { label: 'Titre', value: props.attributes.title, onChange: function(v) { props.setAttributes({ title: v }) } }),
          el(NumberControl, { label: 'ID Catégorie', value: props.attributes.category, min: 0, onChange: function(v) { props.setAttributes({ category: v }) } }),
          el(NumberControl, { label: 'Articles', value: props.attributes.postsPerPage, min: 1, onChange: function(v) { props.setAttributes({ postsPerPage: v }) } }),
          el(TextControl, { label: 'Couleur accent', value: props.attributes.accentColor, onChange: function(v) { props.setAttributes({ accentColor: v }) } })
        )),
        el('div', { className: 'prism-block-preview' }, 'Category Section — ' + (props.attributes.title || 'Catégorie') + ' (' + props.attributes.postsPerPage + ' articles)')
      );
    },
    save: function() { return null; }
  });

  // Featured Posts
  wp.blocks.registerBlockType('prism/featured-posts', {
    apiVersion: 3,
    title: 'Featured Posts',
    icon: 'star-filled',
    category: 'prism',
    attributes: {
      title: { type: 'string', default: 'À la une' },
      postsPerPage: { type: 'number', default: 5 },
      category: { type: 'number', default: 0 }
    },
    edit: function(props) {
      var blockProps = useBlockProps();
      return el('div', blockProps,
        el(Inspector, {}, el(PanelBody, { title: 'Réglages' },
          el(TextControl, { label: 'Titre', value: props.attributes.title, onChange: function(v) { props.setAttributes({ title: v }) } }),
          el(NumberControl, { label: 'Articles', value: props.attributes.postsPerPage, min: 1, onChange: function(v) { props.setAttributes({ postsPerPage: v }) } }),
          el(NumberControl, { label: 'Catégorie (ID)', value: props.attributes.category, min: 0, onChange: function(v) { props.setAttributes({ category: v }) } })
        )),
        el('div', { className: 'prism-block-preview' }, 'Featured Posts — ' + props.attributes.title + ' (' + props.attributes.postsPerPage + ' articles)')
      );
    },
    save: function() { return null; }
  });

  // Magazine Headline
  wp.blocks.registerBlockType('prism/magazine-headline', {
    apiVersion: 3,
    title: 'Magazine Headline',
    icon: 'align-pull-left',
    category: 'prism',
    attributes: {},
    edit: function() {
      var blockProps = useBlockProps();
      return el('div', blockProps,
        el('div', { className: 'prism-block-preview' }, 'Magazine Headline — 1 article hero + 4 en grille (auto)')
      );
    },
    save: function() { return null; }
  });

  // Popular List
  wp.blocks.registerBlockType('prism/popular-list', {
    apiVersion: 3,
    title: 'Popular List', icon: 'list-view', category: 'prism',
    attributes: { postsPerPage: { type: 'number', default: 9 } },
    edit: function(props) {
      var blockProps = useBlockProps();
      return el('div', blockProps,
        el(Inspector, {}, el(PanelBody, { title: 'Réglages' },
          el(NumberControl, { label: 'Articles', value: props.attributes.postsPerPage, min: 1, onChange: function(v) { props.setAttributes({ postsPerPage: v }) } })
        )),
        el('div', { className: 'prism-block-preview' }, 'Popular List — ' + props.attributes.postsPerPage + ' articles numérotés')
      );
    },
    save: function() { return null; }
  });

})(window.wp);
