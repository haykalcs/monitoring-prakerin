<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
  <div class="modal-content">
      <div class="modal-header bg-primary white">
          <h4 class="modal-title" id="myModalLabel17">Profil</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="users-view-image pl-1">
                <img src="{{ asset('uploads/images/'.$data->biography->user->image) }}" class="rounded" alt="avatar" width="150" height="150">
            </div>
            <div class="col">
                <table>
                    <tr>
                        <td class="font-weight-bold pr-5">Nama</td>
                        <td>{{ $data->biography->name ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Alamat</td>
                        <td>{{ $data->biography->user->address ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Email</td>
                        <td>{{ $data->biography->user->email ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Nomor HP</td>
                        <td>{{ $data->biography->user->phone ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="font-weight-bold">Biografi</td>
                        <td>{{ $data->biography->description ?? '' }}</td>
                    </tr>
                </table>
            </div>
        </div>
      </div>
  </div>
</div>