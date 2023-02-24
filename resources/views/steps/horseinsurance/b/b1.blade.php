<div class="frame">
    <div class="frame-contents">

        <div class="bubble bubble-type-d center">
            <div class="bubble-contents">


                <iframe
                    src="{{ $insurley_iframe_url ?? '' }}"
                    frameborder="0"
                    sandbox="allow-scripts
                    allow-same-origin
                    allow-popups
                    allow-forms
                    allow-popups-to-escape-sandbox
                    allow-top-navigation"
                ></iframe>

                <div class="insurley-results-wrapper"></div>

            </div>
        </div>

        {{-- button his hidden, it's still needed for the javascript trigger trigger --}}
        <button style="display: none;" type="button" class="btn1 btn-next">Nästa</button>

    </div>
</div>

<script>
    function setupClient() {
        setClientParams({
            // example input
            hideResultsView: true
        });
    }

    // Event listener for insurley
    window.addEventListener('message', ({data}) => {

        // Empty the wrapper
        if (data.name === 'PAGE_VIEW' && data.value === "SELECT_COMPANIES") {
            $('.insurley-results-wrapper').html('');
        }

        // Fill wrapper with insurances
        if (data.name === 'RESULTS') {
            let html    = '',
                index   = 0;

            // Loop insurances
            for(let insurley of data.value){

                // if not horseinsurance, skip
                if(insurley.insurance.insuranceSubType !== 'horseInsurance'){
                    continue;
                }
                // Loop insurances properties
                for (const property in insurley.insurance) {
                    html += '<input type="hidden" name="insurances['+index+']['+property+']" value="'+insurley.insurance[property]+'" />';
                }
                // Append civicnumber as well
                let civic_number = insurley.personalInformation.PERSONAL_NUMBER || '';
                html += '<input type="hidden" name="insurances['+index+'][civic_number]" value="'+civic_number+'" />';
                index++;
            }

            // Append with html
            $('.insurley-results-wrapper').html(html);

            if(html !== ''){
                $('#main-form').find('.btn-next').trigger('click');
            }
        }
    });

</script>

<script type="text/javascript" src="https://dc.insurely.se/v2/assets/js/dc-bootstrap.js" onload="setupClient()"></script>
