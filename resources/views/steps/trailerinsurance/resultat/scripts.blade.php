<script type="text/javascript">

    function sticky_relocate() {

        var window_top     = $(window).scrollTop();
        footer_top     = $('#resultat-footer').offset().top;
        div_top     = $('#resultat-widget').offset().top;
        div_height     = $('.resultat-widget-contents').height();
        padding     = 20;  // tweak here or get from margins etc

        if (window_top + div_height > footer_top - padding)
            $('.resultat-widget-contents').css({ top: (window_top + div_height - footer_top + padding) * -1 });
        else if (window_top > div_top) {
            $('.resultat-widget-contents').addClass('fixed');
            $('.resultat-widget-contents').css({ top: 0 });
        } else {
            $('.resultat-widget-contents').removeClass('fixed');
        }
    }

    $(document).ready(function() {

        var stickyOffset = $('#resultat-widget').offset().top;
        var stopOffset = $('#resultat-footer').offset().top;

        $(window).scroll(function(){

            var sticky = $('#resultat-widget'),
                scroll = $(window).scrollTop();

            if (scroll >= stickyOffset) {
                sticky.addClass('fixed');
            } else {
                sticky.removeClass('fixed');
            }
        });

        $(window).scroll(sticky_relocate);
        sticky_relocate();
    });

</script>
