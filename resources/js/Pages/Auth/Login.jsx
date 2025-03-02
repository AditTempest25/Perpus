import Checkbox from '@/Components/Checkbox';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Login({ status, canResetPassword }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('login'), {
            onFinish: () => reset('password'),
        });
    };

    return (
        <GuestLayout>
            <Head title="Log in" />

            {/* Notifikasi status jika ada */}
            {status && (
                <div className="mb-4 text-sm font-medium text-green-600">
                    {status}
                </div>
            )}

            {/* Form Login */}
            <form onSubmit={submit}>
                {/* Email */}
                <div>
                    <InputLabel htmlFor="email" value="Email" />

                    <TextInput
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        className="mt-1 block w-full"
                        autoComplete="username"
                        isFocused={true}
                        onChange={(e) => setData('email', e.target.value)}
                    />

                    <InputError message={errors.email} className="mt-2" />
                </div>

                {/* Password + Lupa Password */}
                <div className="mt-4">
                    <div className="flex items-center justify-between">
                        <InputLabel htmlFor="password" value="Password" />
                        {canResetPassword && (
                            <Link
                                href={route('password.request')}
                                className="text-sm text-blue-500 hover:text-blue-700"
                            >
                                Lupa Password?
                            </Link>
                        )}
                    </div>

                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="mt-1 block w-full"
                        autoComplete="current-password"
                        onChange={(e) => setData('password', e.target.value)}
                    />

                    <InputError message={errors.password} className="mt-2" />
                </div>

                {/* Remember Me */}
                <div className="mt-4 block">
                    <label className="flex items-center">
                        <Checkbox
                            name="remember"
                            checked={data.remember}
                            onChange={(e) =>
                                setData('remember', e.target.checked)
                            }
                        />
                        <span className="ml-2 text-sm text-gray-600 dark:text-gray-400">
                            Remember me
                        </span>
                    </label>
                </div>

                {/* Tombol Login */}
                <div className="mt-4">
                    <PrimaryButton className="w-full text-center" disabled={processing}>
                        Login
                    </PrimaryButton>
                </div>
            </form>

            {/* Garis pemisah "or" */}
            <div className="flex items-center my-6">
                <div className="flex-grow border-t border-gray-300"></div>
                <span className="mx-2 text-sm text-gray-400">or</span>
                <div className="flex-grow border-t border-gray-300"></div>
            </div>

            {/* Tombol Login dengan Google */}
            <div>
                <a
                    href="/auth/google"
                    className="inline-flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                >
                    {/* Logo G sederhana */}
                    <svg
                        className="w-5 h-5 mr-2 fill-current"
                        viewBox="0 0 48 48"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path d="M44.5,20H24v8.5h11.9c-1.1,5.2-5.5,9.5-11.9,9.5c-6.6,0-12-5.4-12-12s5.4-12,12-12c3.2,0,6.1,1.2,8.3,3.1L36.1,10
                            C32.5,6.6,28.1,4,23,4C12.3,4,4,12.3,4,23s8.3,19,19,19s19-8.3,19-19C42,22.7,42.7,21.4,44.5,20z"/>
                    </svg>
                    Login dengan Google
                </a>
            </div>
        </GuestLayout>
    );
}
