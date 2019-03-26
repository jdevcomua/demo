<div class="modal fade" id="agreementAcceptModal" tabindex="-2" role="dialog" aria-labelledby="agreementAcceptModalLabel" aria-hidden="true" style="padding-right: 15px;">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div style="margin-top: 30px;"></div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12"><h3 style="text-align: center; font-size: 1.4rem; font-weight: 500; margin-bottom: 30px; text-transform: uppercase">Користуючись цим сайтом ви даєте згоду на
                            <a href="{{route('agreement')}}">Обробку персональних даних</a></h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <a href="{{route('decline-agreement')}}" class="btn btn-default decline" style="padding: 1rem 4rem;">Відмовляюсь</a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{route('accept-agreement')}}" id="acceptAgreementButton" class="btn btn-primary accept" style="padding: 1rem 4rem;">Погоджуюсь</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts-end')
<script>
    let $agreementAcceptModal = $('#agreementAcceptModal');
    let dontShowAgreementModal = {{\Request::route()->getName() === 'agreement' ? 1 : 0}};
    let agreementAccepted = {{\Auth::user() ? \Auth::user()->terms_accepted : 1}};
    if (!agreementAccepted && !dontShowAgreementModal) {
        $agreementAcceptModal.modal({
            backdrop: 'static',
            keyboard: false
        });
    }
    $('#acceptAgreementButton').on('click', function (e) {
        e.preventDefault();
        let route = $(this).attr('href');
        jQuery.ajax({
            url: route,
            type: 'POST',
            contentType: false,
            processData: false,
        });
        $agreementAcceptModal.modal('hide');
    });
</script>
@endsection