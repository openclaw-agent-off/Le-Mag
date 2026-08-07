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
})(window.wp);
