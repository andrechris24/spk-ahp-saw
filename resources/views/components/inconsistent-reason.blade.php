<div class="modal fade text-left" id="inconsistentModal" tabindex="-1"
	role="dialog" aria-labelledby="inconsistentReason" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable"
		role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="inconsistentReason">
					Penyebab Perbandingan Tidak Konsisten
				</h4>
				<button type="button" class="close" data-bs-dismiss="modal"
					aria-label="Close">
					<i data-feather="x"></i>
				</button>
			</div>
			<div class="modal-body">
				<p>Misalnya Anda memasukkan nilai dengan kepentingan sebagai berikut.</p>
				<ul>
					<li>Kriteria C1 : 3 kali lebih penting C2</li>
					<li>Kriteria C1 : 5 kali lebih penting C3</li>
					<li>Kriteria C3 : 9 kali lebih penting C2</li>
				</ul>
				<p>Maka pembobotan tidak konsisten karena C3 lebih penting dibanding C2,
					sedangkan dalam perbandingan C1 dengan C2 dan C3, C2 lebih penting
					dibanding C3.
					<b>Ketidak konsistenan tersebut akan menaikkan nilai Consistency Ratio
						(CR).</b>
					Hasil bobot perbandingan berpasangan antar kriteria dianggap tidak
					konsisten apabila nilai CR >= 0,1.
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-light-secondary"
					data-bs-dismiss="modal">
					<i class="bi bi-x d-block d-sm-none"></i>
					<span class="d-none d-sm-block">Tutup</span>
				</button>
			</div>
		</div>
	</div>
</div>
