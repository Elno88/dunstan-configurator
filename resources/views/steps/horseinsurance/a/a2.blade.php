<div class="frame">
    <div class="frame-contents">
        <div class="bubble bubble-type-a left">
            <div class="bubble-contents bubble-hide-mobile">
                <i class="bubble-help btn-sidebar" data-content="hastforsakring-a-2">?</i>
                <p class="font-heading-mobile mb-md-0">
                    <span class="font-bold">Toppen!</span> När är din häst född?
                </p>
            </div>
        </div>
        <div class="bubble bubble-type-d center">
            <div class="bubble-contents">
                <input type="text" class="datepicker" name="fodelsedatum" autocomplete="off" placeholder="åååå-mm-dd" value="{{ $birthdate ?? '' }}">
            </div>
        </div>
        <button type="button" class="btn1 btn-next">Nästa</button>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.datepicker').dateDropdowns({
            defaultDateFormat: 'dd-mm-yyyy',
            yearLabel: 'ÅR',
            monthLabel: 'MÅNAD',
            dayLabel: 'DAG',
            minYear: '2002',
            daySuffixes: false
        });
        $('select').selectric();
    });
</script>
