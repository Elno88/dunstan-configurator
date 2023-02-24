<div class="frame">
    <div class="frame-contents">

        <div class="bubble bubble-type-a left">
            <div class="bubble-contents">
            	<i class="bubble-help btn-sidebar" data-content="hastforsakring-a-6">?</i>
                <p>När är beräknat datum för fölning?</p>
            </div>
        </div>

        <div class="bubble bubble-type-d center">
            <div class="bubble-contents">
                <input type="text" class="datepicker" name="folningdatum" autocomplete="off" placeholder="åååå-mm-dd" value="{{ $folningdatum ?? '' }}">
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
            maxYear: {{ now()->addYears(1)->format('Y') }},
            minYear: {{ now()->format('Y') }},
            daySuffixes: false
        });
        $('select').selectric();
    });
</script>
