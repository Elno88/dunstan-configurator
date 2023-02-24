{{--
<div class="popup-overlay">
    <div id="popup-test" class="popup">
        <div class="popup-close-x"></div>
        <h3>Popup Headline</h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer sollicitudin est sed iaculis luctus. In est ipsum, mattis venenatis mi eget, varius varius nibh. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p>
        <ul>
            <li>Lorem ipsum dolor sit amet</li>
            <li>Lorem ipsum dolor sit amet</li>
            <li>Lorem ipsum dolor sit amet</li>
            <li>Lorem ipsum dolor sit amet</li>
            <li>Lorem ipsum dolor sit amet</li>
        </ul>
    </div>
</div>
--}}

<script type="text/javascript">

    $(document).ready(function() {

        $('#boxes').on('click', 'li', function() {
            var popup = $(this).data('popup');
            $('body').addClass('popup-open');
            $('.popup-overlay').fadeIn(500);
            $('#popup-'+popup).fadeIn(300);
        });

        $('.popup-close-x').on('click', function() {
            $(this).parent().fadeOut(300);
            $('.popup-overlay').fadeOut(500);
            $('body').removeClass('popup-open');
        });

    });

</script>
