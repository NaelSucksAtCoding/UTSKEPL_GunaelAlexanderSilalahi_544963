<script setup>
import { onMounted, reactive, ref } from 'vue'
import api from '../api'

const STATUS = {
  belum_bayar: 'Belum Bayar',
  dp: 'DP',
  lunas: 'Lunas',
}

const bookings = ref([])
const loading = ref(true)
const pesan = ref(null)

const formTerbuka = ref(false)
const editId = ref(null)
const errors = ref({})
const menyimpan = ref(false)
const form = reactive(formKosong())

const hapusTarget = ref(null)

function formKosong() {
  return {
    nama_penyewa: '',
    nomor_lapangan: 1,
    jam_mulai: '',
    jam_selesai: '',
    total_harga: 0,
    status_pembayaran: 'belum_bayar',
  }
}

// API memakai format "Y-m-d H:i", sedangkan input datetime-local memakai "Y-m-dTH:i".
const keInput = (waktu) => waktu.replace(' ', 'T')
const keApi = (waktu) => waktu.replace('T', ' ')

const rupiah = (angka) =>
  new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(angka)

function tampilkanPesan(teks, jenis = 'sukses') {
  pesan.value = { teks, jenis }
  setTimeout(() => (pesan.value = null), 3000)
}

async function muatBookings() {
  loading.value = true

  try {
    const { data } = await api.get('/bookings')
    bookings.value = data.data
  } catch {
    tampilkanPesan('Gagal memuat data booking.', 'gagal')
  } finally {
    loading.value = false
  }
}

function bukaTambah() {
  Object.assign(form, formKosong())
  editId.value = null
  errors.value = {}
  formTerbuka.value = true
}

function bukaUbah(booking) {
  Object.assign(form, {
    nama_penyewa: booking.nama_penyewa,
    nomor_lapangan: booking.nomor_lapangan,
    jam_mulai: keInput(booking.jam_mulai),
    jam_selesai: keInput(booking.jam_selesai),
    total_harga: booking.total_harga,
    status_pembayaran: booking.status_pembayaran,
  })
  editId.value = booking.id
  errors.value = {}
  formTerbuka.value = true
}

async function simpan() {
  menyimpan.value = true
  errors.value = {}

  const payload = {
    ...form,
    jam_mulai: keApi(form.jam_mulai),
    jam_selesai: keApi(form.jam_selesai),
  }

  try {
    if (editId.value) {
      await api.put(`/bookings/${editId.value}`, payload)
      tampilkanPesan('Booking berhasil diubah.')
    } else {
      await api.post('/bookings', payload)
      tampilkanPesan('Booking berhasil dibuat.')
    }

    formTerbuka.value = false
    await muatBookings()
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors ?? {}
    } else {
      tampilkanPesan('Gagal menyimpan booking.', 'gagal')
    }
  } finally {
    menyimpan.value = false
  }
}

async function hapus() {
  try {
    await api.delete(`/bookings/${hapusTarget.value.id}`)
    tampilkanPesan('Booking berhasil dihapus.')
    await muatBookings()
  } catch {
    tampilkanPesan('Gagal menghapus booking.', 'gagal')
  } finally {
    hapusTarget.value = null
  }
}

onMounted(muatBookings)
</script>

<template>
  <section class="container">
    <div class="page-head">
      <div>
        <h1>Daftar Booking</h1>
        <p class="muted">{{ bookings.length }} booking terdaftar</p>
      </div>
      <button class="btn btn-primary" @click="bukaTambah">+ Tambah Booking</button>
    </div>

    <p v-if="pesan" class="alert" :class="pesan.jenis === 'sukses' ? 'alert-sukses' : ''">{{ pesan.teks }}</p>

    <div class="card table-wrap">
      <p v-if="loading" class="empty">Memuat data...</p>
      <p v-else-if="bookings.length === 0" class="empty">Belum ada booking.</p>
      <table v-else>
        <thead>
          <tr>
            <th>Nama Penyewa</th>
            <th>Lapangan</th>
            <th>Jam Mulai</th>
            <th>Jam Selesai</th>
            <th class="num">Total Harga</th>
            <th>Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="booking in bookings" :key="booking.id">
            <td>{{ booking.nama_penyewa }}</td>
            <td>Lapangan {{ booking.nomor_lapangan }}</td>
            <td>{{ booking.jam_mulai }}</td>
            <td>{{ booking.jam_selesai }}</td>
            <td class="num">{{ rupiah(booking.total_harga) }}</td>
            <td>
              <span class="badge" :class="`badge-${booking.status_pembayaran}`">
                {{ STATUS[booking.status_pembayaran] }}
              </span>
            </td>
            <td class="aksi">
              <button class="btn btn-small" @click="bukaUbah(booking)">Ubah</button>
              <button class="btn btn-small btn-danger" @click="hapusTarget = booking">Hapus</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Form tambah / ubah -->
    <div v-if="formTerbuka" class="modal-backdrop" @click.self="formTerbuka = false">
      <form class="card modal" @submit.prevent="simpan">
        <h2>{{ editId ? 'Ubah Booking' : 'Tambah Booking' }}</h2>

        <label class="field">
          <span>Nama Penyewa</span>
          <input v-model="form.nama_penyewa" type="text" />
          <small v-if="errors.nama_penyewa" class="error">{{ errors.nama_penyewa[0] }}</small>
        </label>

        <div class="grid-2">
          <label class="field">
            <span>Nomor Lapangan</span>
            <input v-model.number="form.nomor_lapangan" type="number" min="1" max="10" />
            <small v-if="errors.nomor_lapangan" class="error">{{ errors.nomor_lapangan[0] }}</small>
          </label>

          <label class="field">
            <span>Status Pembayaran</span>
            <select v-model="form.status_pembayaran">
              <option v-for="(label, value) in STATUS" :key="value" :value="value">{{ label }}</option>
            </select>
            <small v-if="errors.status_pembayaran" class="error">{{ errors.status_pembayaran[0] }}</small>
          </label>
        </div>

        <div class="grid-2">
          <label class="field">
            <span>Jam Mulai</span>
            <input v-model="form.jam_mulai" type="datetime-local" />
            <small v-if="errors.jam_mulai" class="error">{{ errors.jam_mulai[0] }}</small>
          </label>

          <label class="field">
            <span>Jam Selesai</span>
            <input v-model="form.jam_selesai" type="datetime-local" />
            <small v-if="errors.jam_selesai" class="error">{{ errors.jam_selesai[0] }}</small>
          </label>
        </div>

        <label class="field">
          <span>Total Harga (Rp)</span>
          <input v-model.number="form.total_harga" type="number" min="0" step="1000" />
          <small v-if="errors.total_harga" class="error">{{ errors.total_harga[0] }}</small>
        </label>

        <div class="modal-actions">
          <button type="button" class="btn btn-ghost" @click="formTerbuka = false">Batal</button>
          <button type="submit" class="btn btn-primary" :disabled="menyimpan">
            {{ menyimpan ? 'Menyimpan...' : 'Simpan' }}
          </button>
        </div>
      </form>
    </div>

    <!-- Konfirmasi hapus -->
    <div v-if="hapusTarget" class="modal-backdrop" @click.self="hapusTarget = null">
      <div class="card modal modal-small">
        <h2>Hapus Booking?</h2>
        <p>
          Booking atas nama <strong>{{ hapusTarget.nama_penyewa }}</strong> di Lapangan
          {{ hapusTarget.nomor_lapangan }} ({{ hapusTarget.jam_mulai }}) akan dihapus.
        </p>
        <div class="modal-actions">
          <button class="btn btn-ghost" @click="hapusTarget = null">Batal</button>
          <button class="btn btn-danger" @click="hapus">Hapus</button>
        </div>
      </div>
    </div>
  </section>
</template>
