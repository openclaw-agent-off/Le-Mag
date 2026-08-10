(function(wp){
  var el = wp.element.createElement;
  var Inspector = wp.blockEditor.InspectorControls;
  var useBlockProps = wp.blockEditor.useBlockProps;
  var PanelBody = wp.components.PanelBody;
  var TextControl = wp.components.TextControl;
  var ServerSideRender = wp.serverSideRender;
  var Disabled = wp.components.Disabled;
  var __ = wp.i18n.__;

  function NumberControl(props) {
    return el(TextControl, { type: 'number', label: props.label, value: props.value, onChange: function(v) { props.onChange(parseInt(v) || props.min || 0) } });
  }

  // Composant réutilisable : rendu serveur en direct dans l'éditeur + sidebar réglages.
  function DynamicBlock(props) {
    var blockProps = useBlockProps();
    return el('div', blockProps,
      props.inspector,
      el(Disabled, null,
        el(ServerSideRender, {
          block: props.name,
          attributes: props.attributes,
          className: 'lemag-block-editor-render'
        })
      )
    );
  }

  // === POST HERO ===
  wp.blocks.registerBlockType('lemag/post-hero', {
    apiVersion: 3,
    title: 'Post Hero',
    icon: 'cover-image',
    category: 'lemag',
    attributes: {
      postsPerPage: { type: 'number', default: 1 },
      offset: { type: 'number', default: 0 },
      category: { type: 'number', default: 0 }
    },
    edit: function(props) {
      var inspector = el(Inspector, {}, el(PanelBody, { title: __('Réglages', 'lemag-blocks'), initialOpen: true },
        el(NumberControl, { label: __('Articles', 'lemag-blocks'), value: props.attributes.postsPerPage, min: 1, onChange: function(v) { props.setAttributes({ postsPerPage: v }) } }),
        el(NumberControl, { label: __('Offset', 'lemag-blocks'), value: props.attributes.offset, min: 0, onChange: function(v) { props.setAttributes({ offset: v }) } }),
        el(NumberControl, { label: __('Catégorie (ID)', 'lemag-blocks'), value: props.attributes.category, min: 0, onChange: function(v) { props.setAttributes({ category: v }) } })
      ));
      return el(DynamicBlock, { name: 'lemag/post-hero', attributes: props.attributes, inspector: inspector });
    },
    save: function() { return null; }
  });

  // === POST GRID ===
  wp.blocks.registerBlockType('lemag/post-grid', {
    apiVersion: 3,
    title: 'Post Grid',
    icon: 'grid-view',
    category: 'lemag',
    attributes: {
      postsPerPage: { type: 'number', default: 6 },
      offset: { type: 'number', default: 0 },
      columns: { type: 'number', default: 3 },
      category: { type: 'number', default: 0 }
    },
    edit: function(props) {
      var inspector = el(Inspector, {}, el(PanelBody, { title: __('Disposition', 'lemag-blocks'), initialOpen: true },
        el(NumberControl, { label: __('Articles', 'lemag-blocks'), value: props.attributes.postsPerPage, min: 1, onChange: function(v) { props.setAttributes({ postsPerPage: v }) } }),
        el(NumberControl, { label: __('Colonnes', 'lemag-blocks'), value: props.attributes.columns, min: 1, onChange: function(v) { props.setAttributes({ columns: v }) } }),
        el(NumberControl, { label: __('Offset', 'lemag-blocks'), value: props.attributes.offset, min: 0, onChange: function(v) { props.setAttributes({ offset: v }) } }),
        el(NumberControl, { label: __('Catégorie (ID)', 'lemag-blocks'), value: props.attributes.category, min: 0, onChange: function(v) { props.setAttributes({ category: v }) } })
      ));
      return el(DynamicBlock, { name: 'lemag/post-grid', attributes: props.attributes, inspector: inspector });
    },
    save: function() { return null; }
  });

  // === POST CARD ===
  wp.blocks.registerBlockType('lemag/post-card', {
    apiVersion: 3,
    title: 'Post Card',
    icon: 'admin-post',
    category: 'lemag',
    attributes: {
      postId: { type: 'number', default: 0 }
    },
    edit: function(props) {
      var inspector = el(Inspector, {}, el(PanelBody, { title: __('Article', 'lemag-blocks'), initialOpen: true },
        el(NumberControl, { label: __('ID article', 'lemag-blocks'), value: props.attributes.postId, min: 0, onChange: function(v) { props.setAttributes({ postId: v }) } })
      ));
      return el(DynamicBlock, { name: 'lemag/post-card', attributes: props.attributes, inspector: inspector });
    },
    save: function() { return null; }
  });

  // === CATEGORY SECTION ===
  wp.blocks.registerBlockType('lemag/category-section', {
    apiVersion: 3,
    title: 'Category Section',
    icon: 'category',
    category: 'lemag',
    attributes: {
      title: { type: 'string', default: '' },
      category: { type: 'number', default: 0 },
      postsPerPage: { type: 'number', default: 5 },
      accentColor: { type: 'string', default: '' }
    },
    edit: function(props) {
      var inspector = el(Inspector, {}, el(PanelBody, { title: __('Catégorie', 'lemag-blocks'), initialOpen: true },
        el(TextControl, { label: __('Titre', 'lemag-blocks'), value: props.attributes.title, onChange: function(v) { props.setAttributes({ title: v }) } }),
        el(NumberControl, { label: __('ID Catégorie', 'lemag-blocks'), value: props.attributes.category, min: 0, onChange: function(v) { props.setAttributes({ category: v }) } }),
        el(NumberControl, { label: __('Articles', 'lemag-blocks'), value: props.attributes.postsPerPage, min: 1, onChange: function(v) { props.setAttributes({ postsPerPage: v }) } }),
        el(TextControl, { label: __('Couleur accent', 'lemag-blocks'), value: props.attributes.accentColor, onChange: function(v) { props.setAttributes({ accentColor: v }) } })
      ));
      return el(DynamicBlock, { name: 'lemag/category-section', attributes: props.attributes, inspector: inspector });
    },
    save: function() { return null; }
  });

  // === FEATURED POSTS ===
  wp.blocks.registerBlockType('lemag/featured-posts', {
    apiVersion: 3,
    title: 'Featured Posts',
    icon: 'star-filled',
    category: 'lemag',
    attributes: {
      title: { type: 'string', default: 'À la une' },
      postsPerPage: { type: 'number', default: 5 },
      category: { type: 'number', default: 0 }
    },
    edit: function(props) {
      var inspector = el(Inspector, {}, el(PanelBody, { title: __('Réglages', 'lemag-blocks'), initialOpen: true },
        el(TextControl, { label: __('Titre', 'lemag-blocks'), value: props.attributes.title, onChange: function(v) { props.setAttributes({ title: v }) } }),
        el(NumberControl, { label: __('Articles', 'lemag-blocks'), value: props.attributes.postsPerPage, min: 1, onChange: function(v) { props.setAttributes({ postsPerPage: v }) } }),
        el(NumberControl, { label: __('Catégorie (ID)', 'lemag-blocks'), value: props.attributes.category, min: 0, onChange: function(v) { props.setAttributes({ category: v }) } })
      ));
      return el(DynamicBlock, { name: 'lemag/featured-posts', attributes: props.attributes, inspector: inspector });
    },
    save: function() { return null; }
  });

  // === MAGAZINE HEADLINE ===
  wp.blocks.registerBlockType('lemag/magazine-headline', {
    apiVersion: 3,
    title: 'Magazine Headline',
    icon: 'align-pull-left',
    category: 'lemag',
    attributes: {},
    edit: function(props) {
      var inspector = el(Inspector, {}, el(PanelBody, { title: __('Réglages', 'lemag-blocks'), initialOpen: true },
        el('p', null, __('1 article hero + 4 en grille (auto). Aucun réglage.', 'lemag-blocks'))
      ));
      return el(DynamicBlock, { name: 'lemag/magazine-headline', attributes: props.attributes, inspector: inspector });
    },
    save: function() { return null; }
  });

  // === POPULAR LIST ===
  wp.blocks.registerBlockType('lemag/popular-list', {
    apiVersion: 3,
    title: 'Popular List',
    icon: 'list-view',
    category: 'lemag',
    attributes: {
      postsPerPage: { type: 'number', default: 9 }
    },
    edit: function(props) {
      var inspector = el(Inspector, {}, el(PanelBody, { title: __('Réglages', 'lemag-blocks'), initialOpen: true },
        el(NumberControl, { label: __('Articles', 'lemag-blocks'), value: props.attributes.postsPerPage, min: 1, onChange: function(v) { props.setAttributes({ postsPerPage: v }) } })
      ));
      return el(DynamicBlock, { name: 'lemag/popular-list', attributes: props.attributes, inspector: inspector });
    },
    save: function() { return null; }
  });

})(window.wp);