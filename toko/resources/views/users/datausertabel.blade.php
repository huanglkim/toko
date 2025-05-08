<div class="row g-3">
    @foreach ($Users as $user)
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-between p-3">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-2">{{ $user->nama }}</h5>
                            <ul class="list-unstyled small text-muted">
                                <li><i class="fas fa-map-marker-alt me-2 text-secondary"></i>Alamat:
                                    {{ $user->alamat ?? '-' }}</li>
                                <li><i class="fas fa-phone me-2 text-secondary"></i>WhatsApp: {{ $user->wa ?? '-' }}</li>
                                <li><i class="fas fa-user me-2 text-secondary"></i>Username: {{ $user->username }}</li>
                                <li><i class="fas fa-briefcase me-2 text-secondary"></i>Jabatan:
                                    {{ $user->Role->nama_jabatan ?? '-' }}</li>
                                <li>
                                    <i class="fas fa-toggle-on me-2 text-secondary"></i>Status:
                                    <span class="badge {{ $user->status == 1 ? 'badge-success' : 'badge-danger' }}">
                                        {{ $user->status == 1 ? 'AKTIF' : 'TIDAK AKTIF' }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <div class="ms-3 text-center">
                            <img src="{{ $user->foto ? asset('toko/public/storage/' . $user->foto) : asset('images/default-user.png') }}"
                                 alt="Foto {{ $user->nama }}" class="img-fluid rounded-circle border"
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        </div>
                        

                    </div>
                </div>

                <div class="card-footer bg-light text-end py-2">
                    <a href="http://wa.me/{{ $user->wa }}" target="_blank" class="btn btn-sm btn-success me-1">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    @if (Auth()->User()->role_id == 1)
                        <button class="btn btn-sm btn-warning me-1"
                            onclick="OtEdit('{{ $user->id }}')">Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="OtDelete('{{ $user->id }}')">Hapus</button>
                    @else
                        <button class="btn btn-sm btn-warning me-1"
                            onclick="editForm('{{ $user->id }}')">Edit</button>
                        <button class="btn btn-sm btn-danger"
                            onclick="deleteData('{{ $user->id }}')">Hapus</button>
                    @endif
                </div>
            </div>
        </div>
    @endforeach

    <div class="col-12">
        <div class="mt-3">
            {!! $Users->links() !!}
        </div>
    </div>
</div>
