(function($){
  wp.customize('lemag_primary_color', function(v){ v.bind(function(c){ document.documentElement.style.setProperty('--red', c); }); });
  wp.customize('lemag_bg_color', function(v){ v.bind(function(c){ document.documentElement.style.setProperty('--white', c); document.body.style.background = c; }); });
  wp.customize('lemag_text_color', function(v){ v.bind(function(c){ document.documentElement.style.setProperty('--black', c); document.body.style.color = c; }); });
  wp.customize('lemag_dark_mode', function(v){
    v.bind(function(on){
      var root = document.documentElement.style;
      if (on) {
        root.setProperty('--black', '#eee');
        root.setProperty('--white', '#111');
        root.setProperty('--gray', '#999');
        root.setProperty('--gray-light', '#1a1a1a');
        root.setProperty('--border', '#333');
      } else {
        var bg = wp.customize('lemag_bg_color')();
        var text = wp.customize('lemag_text_color')();
        var grayLight = '#F3F4F6', border = '#E5E7EB', gray = '#6B7280';
        root.setProperty('--black', text || '#111');
        root.setProperty('--white', bg || '#fff');
        root.setProperty('--gray', gray);
        root.setProperty('--gray-light', grayLight);
        root.setProperty('--border', border);
      }
    });
  });
  wp.customize('lemag_sticky_header', function(v){ v.bind(function(c){ document.querySelector('.site-header').style.position = c ? 'sticky' : 'static'; }); });
})(jQuery);
