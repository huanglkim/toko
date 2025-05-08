<div class="modal fade" id="importExcel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="post" action="{{ url('/importbarang') }}" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Import Excel</h5>
                </div>
                <div class="modal-body">

                    {{ csrf_field() }}

                    <label>Pilih file excel</label>
                    <div class="form-group">
                        <input type="file" id="file" name="file" required="required">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" onclick="process()">Import</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    function process() {
        var file = document.getElementById('file').value;
        if (file.length === 0) {
            alert('Masukkan data terlebih dahulu');
        } else {
            Swal.fire({
                title: "Loading...",
                text: "Harap Tunggu",
                imageUrl: '{{ publicfolder() }}' + '/images/loadingt.gif',
                // imageWidth: 400,
                // imageHeight: 200,
                imageAlt: 'PROSES UPLOAD',
            })
        }
    };
</script>
