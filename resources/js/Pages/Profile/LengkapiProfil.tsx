import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import { FormEventHandler, ChangeEventHandler } from 'react';

interface Petugas {
    id_petugas: number;
    nama: string;
    nik: string;
    jabatan: string;
    foto: string | null;
    status: string;
}

interface User {
    id: number;
    email: string;
    whatsapp: string;
}

interface Props {
    user: User;
    petugas: Petugas | null;
}

export default function LengkapiProfil({ user, petugas }: Props) {
    const { data, setData, post, processing, errors } = useForm({
        nama: petugas?.nama || '',
        nik: petugas?.nik || '',
        foto: null as File | null,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        post(route('profile.lengkapi.update'), {
            forceFormData: true,
        });
    };

    const handleFileChange: ChangeEventHandler<HTMLInputElement> = (e) => {
        if (e.target.files && e.target.files[0]) {
            setData('foto', e.target.files[0]);
        }
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Lengkapi Profil Petugas
                </h2>
            }
        >
            <Head title="Lengkapi Profil" />

            <div className="py-12">
                <div className="mx-auto max-w-2xl sm:px-6 lg:px-8">
                    <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900 dark:text-gray-100">
                            <div className="mb-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg">
                                <p className="text-sm text-yellow-800 dark:text-yellow-300">
                                    ⚠️ Akun Anda belum diaktivasi. Silakan lengkapi data profil untuk mengaktifkan akun.
                                </p>
                            </div>

                            <form onSubmit={submit} encType="multipart/form-data">
                                {/* Email (readonly) */}
                                <div className="mb-4">
                                    <InputLabel htmlFor="email" value="Email" />
                                    <TextInput
                                        id="email"
                                        type="text"
                                        value={user.email}
                                        className="mt-1 block w-full bg-gray-100 dark:bg-gray-700"
                                        disabled
                                    />
                                </div>

                                {/* Nama */}
                                <div className="mb-4">
                                    <InputLabel htmlFor="nama" value="Nama Lengkap" />
                                    <TextInput
                                        id="nama"
                                        type="text"
                                        value={data.nama}
                                        className="mt-1 block w-full"
                                        onChange={(e) => setData('nama', e.target.value)}
                                        required
                                    />
                                    <InputError message={errors.nama} className="mt-2" />
                                </div>

                                {/* NIK */}
                                <div className="mb-4">
                                    <InputLabel htmlFor="nik" value="NIK" />
                                    <TextInput
                                        id="nik"
                                        type="text"
                                        value={data.nik}
                                        className="mt-1 block w-full"
                                        maxLength={16}
                                        onChange={(e) => setData('nik', e.target.value)}
                                        required
                                    />
                                    <InputError message={errors.nik} className="mt-2" />
                                </div>

                             
                                {/* Foto */}
                                <div className="mb-4">
                                    <InputLabel htmlFor="foto" value="Foto (Opsional)" />
                                    <input
                                        id="foto"
                                        type="file"
                                        accept="image/*"
                                        className="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                        onChange={handleFileChange}
                                    />
                                    <InputError message={errors.foto} className="mt-2" />
                                </div>

                                {/* Submit */}
                                <div className="flex items-center justify-end mt-6">
                                    <PrimaryButton disabled={processing}>
                                        {processing ? 'Menyimpan...' : 'Simpan & Aktifkan Akun'}
                                    </PrimaryButton>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
