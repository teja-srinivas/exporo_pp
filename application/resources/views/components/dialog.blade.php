<div id="overlay" onclick="hideDialog()">
    <div
        id="content"
        class="rounded shadow-sm bg-white d-flex align-items-center justify-content-center px-5 py-5"
    >
        <div>
            <div class="mb-3">
                {{ $message }}
            </div>
            <div class="d-flex justify-content-center">
                <div>
                    <button class="btn btn-sm btn-danger mr-2">{{ $confirmLabel }}</button>
                </div>
                <div>
                    <a class="btn btn-sm btn-primary text-white" onclick="hideDialog()">Abbrechen</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showDialog() {
    document.getElementById("overlay").style.display = "block";
}

function hideDialog() {
    document.getElementById("overlay").style.display = "none";
}
</script>

<style>
#overlay {
    position: fixed;
    display: none;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,0.5);
    z-index: 1000000;
    cursor: pointer;
}

#content{
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%,-50%);
    -ms-transform: translate(-50%,-50%);
}
</style>
