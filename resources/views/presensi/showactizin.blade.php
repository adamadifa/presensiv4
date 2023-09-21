<ul class="action-button-list">
    @if (empty($izin->status_approved))
    <li>
        <a href="#" id="deletebutton" class="btn btn-list text-danger" data-dismiss="modal" data-toggle="modal" data-target="#DialogBasic">
            <span>
                <ion-icon name="trash-outline"></ion-icon>
                Delete
            </span>
        </a>
    </li>
    @endif

    @if (!empty($izin->sid))
    <li>
        <a href="/izin/{{ $id }}/showsid" class="btn btn-list text-primary">
            <span>
                <ion-icon name="document-attach-outline"></ion-icon>
                Lihat SID
            </span>
        </a>
    </li>
    @endif
    <li class="action-divider"></li>
    <li>
        <a href="#" class="btn btn-list text-danger" data-dismiss="modal">
            <span>
                <ion-icon name="close-outline"></ion-icon>
                Close
            </span>
        </a>
    </li>
</ul>

<script>
    $(function() {
        $("#deletebutton").click(function(e) {
            $("#hapuspengajuan").attr('href', '/izin/' + '{{ $id }}/delete');
        });
    });

</script>
