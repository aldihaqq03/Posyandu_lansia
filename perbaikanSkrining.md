# Plan - IVA/Sadanis locking, PUMA gender auto-populate, Smoking Synchronization, and Double Validation (UX & Backend)

This plan details the changes required to:
1. Lock the **IVA / Sadanis** field in Skrining Utama for male lansia.
2. Auto-populate the PUMA questionnaire gender in Skrining PPOK from the database by ID Lansia (similar to how pekerjaan is handled).
3. Implement smoking status synchronization between Skrining Utama and Skrining PPOK when both are active in the schedule (Utama is primary, PPOK follows, auto-fills and locks). When only one is active, allow manual input.
4. Implement conditional detail inputs validation and formatting for smoking status in both screenings.
5. Implement double validation (frontend/UX and backend) for all input options in both Skrining Utama and Skrining PPOK.

## Proposed Changes

### 1. View Layout Modification (`index.blade.php`)

#### [MODIFY] [index.blade.php](file:///d:/semester%204/frame%20wrok%20mobile/Posyandu_lansia/resources/views/admin/skrining/index.blade.php)
- Update `<select name="id_lansia" id="select-lansia">` to include the `data-jenis-kelamin` attribute on every `<option>` element.
- Update the Javascript event listener for `#select-lansia` change to:
  - Check the selected lansia's gender.
  - If Male (`L`):
    - Lock, disable, and uncheck the `iva_sadanis` radio buttons in Skrining Utama.
    - Set the visual indicator for disabled section (`disabled-locked` class).
    - Set the read-only input `puma-jk-display` to "Laki-laki (skor 1)" and hidden input `puma_jenis_kelamin` to `1`.
  - If Female (`P`):
    - Enable the `iva_sadanis` radio buttons and make them `required`.
    - Set the read-only input `puma-jk-display` to "Perempuan (skor 0)" and hidden input `puma_jenis_kelamin` to `0`.
- Update the PUMA Jenis Kelamin UI in the PPOK form:
  - Change the radio buttons to a read-only text input (`puma-jk-display`) and a hidden input (`puma-jk-hidden`).
- Add a style rule for `.disabled-locked` under `@push('styles')`.
- Add conditional detail inputs and synchronization logic in JS:
  - Check if both steps (`#step-utama` and `#step-ppok`) are present.
  - If both active:
    - Sync `merokok_ppok` selection with Utama's `merokok` selection, and lock `merokok_ppok` (disabled).
    - Enable synced fields temporarily before form submit to ensure values are sent to the server.
  - If `merokok` / `merokok_ppok` is "Tidak":
    - Set `jenis_rokok` = null, `rokok_per_hari` = 0, `lama_merokok` = 0, `pack_years` = 0.
    - Disable all conditional smoking details.
    - Remove `required` attributes.
  - If "Ya":
    - Enable conditional smoking details.
    - Make them `required`.
    - Compute Pack Years dynamically on-the-fly: `pack_years = (rokok_per_hari / 20) * lama_merokok`.
- Update the frontend validator `validateCurrentStep()` to:
  - Skip validation for disabled fields.
  - Properly highlight validation errors for radio buttons and checkboxes.
  - Ensure correct scrolling to invalid sections including radio groups and checkboxes.

### 2. Backend Controller Modification (`SkriningController.php`)

#### [MODIFY] [SkriningController.php](file:///d:/semester%204/frame%20wrok%20mobile/Posyandu_lansia/app/Http/Controllers/SkriningController.php)
- Include `jenis_kelamin` in the `Lansia::select(...)` query when fetching the selected lansia.
- Implement backend validation for `SKRINING_UTAMA` fields:
  - Validate `gula_darah`, `kolesterol`, `iva_sadanis` (conditionally required for females, null for males), `merokok`, `merokok_kategori`, `paparan_asap_rokok`, `paparan_asap_rokok_frekuensi`, and all lifestyle items.
- Implement backend validation for `SKRINING_PPOK` fields:
  - Validate `status_vaksinasi_covid`, `kurang_aktivitas_fisik`, `kurang_sayur_buah`, `merokok_ppok`, `jenis_rokok`, `konsumsi_alkohol_ppok`, `rapid_antigen`, `kadar_co_ppm`, and all PUMA questionnaire items.
  - If both screenings exist, validate that PPOK's `merokok_ppok` matches Utama's `merokok` to prevent data inconsistencies.
- Fix PPOK repository mapping bugs in the transaction logic:
  - Map `merokok` and `konsumsi_alkohol` correctly to use `$request->merokok_ppok` and `$request->konsumsi_alkohol_ppok`.
  - Map `riwayat_penyakit_keluarga` and `riwayat_penyakit_sendiri` correctly to use `$request->riwayat_penyakit_keluarga_ppok` and `$request->riwayat_penyakit_sendiri_ppok`.
  - Save `puma_jenis_kelamin` automatically derived from the database record of the selected lansia.

## Verification Plan

### Manual Verification
- Test selecting a male lansia:
  - Verify that the **IVA / Sadanis** field becomes locked (grayed out, unselectable, and not required).
  - Verify that in Skrining PPOK, the **Jenis Kelamin** field automatically updates to "Laki-laki (skor 1)".
  - Verify that submitting without filling mandatory fields triggers validation errors on the front-end, styling the invalid sections red and scrolling to them.
- Test selecting a female lansia:
  - Verify that the **IVA / Sadanis** field is enabled and selectable.
  - Verify that in Skrining PPOK, the **Jenis Kelamin** field automatically updates to "Perempuan (skor 0)".
- Test smoking synchronization:
  - Set Merokok = "Ya" in Skrining Utama. Verify PPOK Merokok is set to "Ya" and disabled. Ensure smoking details inputs are enabled and required.
  - Set Merokok = "Tidak" in Skrining Utama. Verify PPOK Merokok is set to "Tidak" and disabled. Ensure smoking details are disabled and set to 0.
- Test submitting the form with valid data and ensure all records are saved correctly in database tables (`skrining_utama`, `skrining_ppok`).
- Validate backend error handling by temporarily removing frontend HTML validation and trying to submit empty fields.
