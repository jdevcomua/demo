<div class="modal fade" id="scanBadgeNotFound" tabindex="-2" role="dialog" aria-labelledby="scanBadgeNotFoundModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12" style="display: flex; justify-content: center; margin-bottom: 30px;">
                        <img style="height: 159px;" src="{{asset('img/icon/cancel.svg')}}" alt="success">
                    </div>
                    <div class="col-sm-12"><h3 style="text-align: center; font-size: 2rem; font-weight: bold; margin-bottom: 30px;">На жаль, тварину, якій належить
                            <br> жетон зі сканованим QR-кодом, <br> в реєстрі не знайдено.</h3>
                        <div class="col-sm-12">
                            <p style="text-align: center; font-size: 1.1rem; color: #000; margin-bottom: 30px;">
                                Будь ласка, залишіть інформацію про знайдену тварину, натиснувши посилання нижче.
                            </p>
                        </div>
                    </div>
                </div>
                <div style="display: flex; justify-content: center">
                    <a href="" class="btn search btn-primary" style="display: inline-block; line-height: 1.3;" id="informAboutFoundAnimal">Повідомити про
                        <br>знайдену тварину</a>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts-end')
<script>
    $('#scanBadgeNotFound').modal('show');

    $('#informAboutFoundAnimal').on('click', function (e) {
        e.preventDefault();
        $('#scanBadgeNotFound').modal('hide');
        setTimeout(function () {
            $('#foundAnimalModal').modal('show');
        }, 500);
    });
</script>
@endsection