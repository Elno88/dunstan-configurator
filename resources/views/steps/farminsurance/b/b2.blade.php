<div class="frame">
    <div class="frame-contents">
        <div class="bubble bubble-type-a left">
            <div class="bubble-contents">
            	<i class="bubble-help btn-sidebar" data-content="gardsforsakring-b-2">?</i>
                <p>Välj vilket försäkringsbolag du har idag, så hämtar vi information om dina befintliga försäkringar och din gård.</p>
                <p><a style="color: #093b35;" class="btn-sidebar" href="#!" data-content="gardsforsakring-b-2">Varför gör vi detta?</a></p>
            </div>
        </div>
        <div class="bubble bubble-type-d center">
            <div class="bubble-contents">
                <input type="hidden" name="insurely" value="" />
                <iframe
                    id="insurely-data-aggregation"
                    title="insurely-data-aggregation"
                    src="{{ $insurley_iframe_url ?? '' }}"
                    frameborder="0"
                    sandbox="allow-scripts
                    allow-same-origin
                    allow-popups
                    allow-forms
                    allow-popups-to-escape-sandbox
                    allow-top-navigation"
                ></iframe>
            </div>
        </div>
        {{-- button his hidden, it's still needed for the javascript trigger trigger --}}
        <button style="display: none;" type="button" class="btn1 btn-next">Gå vidare</button>
    </div>
</div>
<script>
    window.addEventListener('message', ({data}) => {
        // Empty the wrapper
        if (data.name === 'PAGE_VIEW' && data.value === "SELECT_COMPANIES") {
            $('#main-form').find('input[name=insurely]').val('');
        }
        // Fill wrapper with insurances and trigger next on button
        if (data.name === 'RESULTS') {
            $('#main-form').find('input[name=insurely]').val(JSON.stringify(data.value));
            $('#main-form').find('.btn-next').trigger('click');
        }
    });
</script>

<script type="text/javascript" src="https://blocks.insurely.com/assets/bootstrap.js"></script>

<script async>
    window.insurely = {
        config: {
            customerId: '{{ $customerId ?? null }}',
            configName: '{{ $configName ?? null }}',
        },
    };
</script>
