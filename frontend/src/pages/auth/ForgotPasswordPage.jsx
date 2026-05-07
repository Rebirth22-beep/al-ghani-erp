import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { z } from 'zod'
import { Link } from 'react-router-dom'
import { useMutation } from '@tanstack/react-query'
import { authService } from '@/services/authService'
import { Input } from '@/components/ui/Input'
import { Button } from '@/components/ui/Button'
import { Alert } from '@/components/ui/Alert'
import { FadeIn } from '@/components/ui/FadeIn'
import { ROUTES } from '@/constants/routes'

const schema = z.object({
  email: z.string().email('Invalid email'),
})

export function ForgotPasswordPage() {
  const { register, handleSubmit, formState: { errors } } = useForm({ resolver: zodResolver(schema) })

  const { mutate, isPending, isSuccess, error } = useMutation({
    mutationFn: (data) => authService.forgotPassword(data.email),
  })

  return (
    <div className="min-h-screen bg-gradient-to-br from-brand-dark via-brand to-primary-400 flex items-center justify-center p-4">
      <FadeIn className="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8">
        <h1 className="text-xl font-bold text-gray-900 mb-2">Reset Password</h1>
        <p className="text-sm text-gray-500 mb-6">Enter your email to receive reset instructions.</p>

        {isSuccess && <Alert type="success" message="Reset link sent. Check your email." className="mb-4" />}
        {error && <Alert type="error" message={error.response?.data?.message || 'Request failed.'} className="mb-4" />}

        <form onSubmit={handleSubmit(mutate)} className="space-y-4">
          <Input label="Email" type="email" error={errors.email?.message} {...register('email')} />
          <Button type="submit" loading={isPending} className="w-full">Send Reset Link</Button>
        </form>

        <Link to={ROUTES.LOGIN} className="block text-center text-sm text-brand mt-4 hover:underline">
          Back to Sign In
        </Link>
      </FadeIn>
    </div>
  )
}
