<div class="modal fade" id="firstmodal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-body text-center p-5">

                <lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop"
                    colors="primary:#f7b84b,secondary:#405189" style="width:130px;height:130px">
                </lord-icon>
                <h4 class="mb-3">Upload Data Rencana Kebutuhan Unit!</h4>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-evenly">
                            <p class="text-muted mb-0"><i
                                    class="mdi mdi-numeric-1-circle text-success fs-18 align-middle me-2 rounded-circle shadow"></i>Completed
                            </p>
                            <p class="text-muted mb-0"><i
                                    class="mdi mdi-numeric-3-circle text-info fs-18 align-middle me-2 rounded-circle shadow"></i>In
                                Progress</p>
                            <p class="text-muted mb-0"><i
                                    class="mdi mdi-numeric-2-circle text-primary fs-18 align-middle me-2 rounded-circle shadow"></i>To
                                Do</p>
                        </div>
                    </div>
                    <div class="progress animated-progress bg-soft-primary rounded-bottom rounded-0"
                        style="height: 6px;">
                        <div class="progress-bar bg-success rounded-0" role="progressbar" style="width: 30%"
                            aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                        <div class="progress-bar bg-info rounded-0" role="progressbar" style="width: 50%"
                            aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                        <div class="progress-bar rounded-0" role="progressbar" style="width: 20%" aria-valuenow="20"
                            aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

                <form action="{{ route('rkbu_barjas_admins.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mt-1 pt-1">
                        <input type="file" name="file" class="form-control form-control-sm" id="formSizeSmall">

                        <h5 class="mt-4">Download Format Upload File</h5>

                        <div class="d-flex justify-content-center mt-2">
                            <a href="{{ route('rkbu_barjas_admins.downloadReport') }}" class="btn btn-outline-primary">
                                Download Format 🥳
                            </a>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center mt-4">
                        <a href="" class="btn btn-secondary light">Back</a>
                        <button type="submit" class="btn btn-primary">
                            Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
