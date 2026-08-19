### Core Tables:
- **tb_pegawai**: Employee data (id, kode_pegawai, nik_pegawai, full_name, nick_name, no_telp, alamat, jabatan→tb_jabatan.id, golongan→tb_golongan.id, tgl_lahir, gender['L','P'])
- **users**: User accounts (id, name, email, kode_pegawai→tb_pegawai.kode_pegawai, profile_pic, is_active)
- **tb_attendance**: Clock-in attendance (id, kode_pegawai→tb_pegawai.kode_pegawai, waktuori, jam_masuk, longitude, latitude, status, verified, photoURL, position_status[1=WFO, 2=WFH, 3=VT])
- **tb_attendance_out**: Clock-out attendance (id, kode_pegawai→tb_pegawai.kode_pegawai, waktuori, jam_keluar, longitude, latitude, status, verified)
- **tb_attendance_inquiries**: Route/temporary attendance requests (id, kode_pegawai→tb_pegawai.kode_pegawai, type_absen['masuk','keluar'], position_status, longitude, latitude, waktu_absen, keterangan, no_vt, bukti, status['pending','approved','rejected'], acted_by→users.id, acted_at, rejection_reason)
- **tb_jabatan**: Positions (id, nama_jabatan, divisi→tb_division.id, penempatan→tb_placement.id)
- **tb_golongan**: Employee grades (id, nama_golongan, alias)
- **tb_division**: Divisions (id, kode_divisi, nama_divisi)
- **tb_placement**: Work placements (id, kode_penempatan, penempatan, alamat, longitude, latitude, radius)
- **tb_jadwal**: Work schedules (id, id_golongan→tb_golongan.id, hari, jam_masuk, jam_keluar, break_start, break_end)
- **tb_overtime**: Overtime logs (id, kode_pegawai→tb_pegawai.kode_pegawai, start_time, end_time, notes, status, fee)

### Receivables & Collectors:
- **tb_collect**: Collector reports (id, no_sr, bill_type, kode_pegawai→tb_pegawai.kode_pegawai, title, status[0=draft,1=approved,2=submitted,3=rejected,4=revision], payment_amount, assign_date)
- **tb_collect_tasks**: IDC Non-VAT receivables (id, no_sr, customer_name, total_bill, remaining_bill, assign_to, bill_status)
- **tb_collect_tasks_ppn**: IDC VAT receivables (id, no_sr, sales_invoice, tax_invoice, customer_name, total_bill, remaining_bill, assign_to, bill_status)
- **tb_collect_idy_ppn**: IDY VAT receivables (id, no_sr, sales_invoice, tax_invoice, customer_name, total_bill, remaining_bill, assign_to, bill_status)

### Driver & Sales:
- **tb_drivers**: Driver reports (id, no_sr, kode_pegawai→tb_pegawai.kode_pegawai, title, lokasi, status[0=draft,1=submitted,2=approved,3=rejected], assign_date)
- **tb_sales**: Sales reports (id, judul, kode_pegawai→tb_pegawai.kode_pegawai, pengajuan, jenis, status, catatan)

### Work Orders & Production:
- **tb_spk**: Work orders / SPK (id, nomor_spk, nama_customer, alamat_kirim, nama_barang, berat_timbangan, jumlah, harga, ppn, total, tipe_timbangan, status_approval, deadline)
- **tb_produksi**: SPK production (id, id_spk→tb_spk.id, assign_to→users.id, packing_list)
- **tb_purchasing_request**: Purchasing requests (id, id_spk→tb_spk.id, kode_item, nama_item, qty, satuan)
- **tb_laporan_fondasi**: Field foundation SPK report (id, id_spk→tb_spk.id, judul, dokumentasi, keterangan, status_pengerjaan, added_by→users.id)
- **tb_packing_list**: Product packing items (id, id_barang, nama_part, jumlah, satuan, pack)
- **tb_packing_list_kit**: SPK packing kit details (id, id_spk→tb_spk.id, id_barang_produksi, nama_kit, jumlah_kit, satuan_kit, nama_customer, peti)

### Invoice:
- **tb_invoice**: Invoices (id, nomor_btt, tgl_btt, nama_customer, tipe_invoice, status_pengiriman, tipe_tagihan)

### Leave:
- **tb_leave_requests**: Leave requests (id, user_id→users.id, leave_type_id→tb_leave_types.id, start_date, end_date, total_days, reason, status[pending,approved_by_supervisor,approved,rejected,cancelled])
- **tb_leave_types**: Leave types (id, name, code, default_days, requires_attachment)
- **tb_leave_balances**: Leave balances (id, user_id→users.id, year, total_quota, used_quota)

### Others & Teams:
- **tb_technician**: Technicians & VT status (no_vt, kode_pegawai, status, keterangan)
- **tb_technician_points**: Technician points (kode_pegawai, id_vt, poin, type, status)
- **tb_point_transactions**: Point redeems (id, transaction_id, quartal, year, point_type, kode_pegawai→tb_pegawai.kode_pegawai, redeemed_by, total_points, status)
- **tb_teams**: Teams (name, leader_id)
- **tb_team_members**: Team members (team_id, kode_pegawai)
- **tb_holidays**: National holidays (date, name)
- **tb_log**: Activity logs (user_id, user_action, ip_address, user_agent)
- **roles & permissions**: via Spatie (roles, permissions, model_has_roles, model_has_permissions, role_has_permissions)

### Notifications & System:
- **notifications**: System notifications (id [UUID], type, notifiable_type, notifiable_id, data, read_at, created_at, updated_at)
- **announcements**: System announcements (id, title, description, status, file_path, target_type, target_roles, target_users)
- **announcement_reads**: Read status of announcements (id, announcement_id→announcements.id, user_id→users.id, read_at)
- **tb_backups**: Database backup records (id, name, type, file, user_id, status)
- **tb_big_event**: Large event details (id, name, description, location, start_date, end_date, status)
- **tb_big_event_participant**: Event participants (id, big_event_id→tb_big_event.id, user_id→users.id, visitor_api, redirect_to)
- **tb_big_event_participant_visitor**: Participant visitor logs (id, participant_id→tb_big_event_participant.id, ip, ua, second_bucket, real_info)
- **tb_chat_conversations**: Chatbot sessions (id, user_id→users.id, title, interaction_id, api_key_index, persona)
- **tb_chat_messages**: Chatbot messages (id, conversation_id→tb_chat_conversations.id, role, content, status)

