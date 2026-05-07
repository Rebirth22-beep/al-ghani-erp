import { useForm } from 'react-hook-form'
import { zodResolver } from '@hookform/resolvers/zod'
import { z } from 'zod'
import { useNavigate, useLocation } from 'react-router-dom'
import { useMutation } from '@tanstack/react-query'
import { authService } from '@/services/authService'
import { useAuthStore } from '@/stores/authStore'
import { Input } from '@/components/ui/Input'
import { Button } from '@/components/ui/Button'
import { Alert } from '@/components/ui/Alert'
import { FadeIn } from '@/components/ui/FadeIn'
import { ROUTES } from '@/constants/routes'

const schema = z.object({
  email:    z.string().email('Invalid email'),
  password: z.string().min(6, 'Password must be at least 6 characters'),
})

export function LoginPage() {
  const navigate  = useNavigate()
  const location  = useLocation()
  const setAuth   = useAuthStore((s) => s.setAuth)
  const from      = location.state?.from?.pathname || ROUTES.DASHBOARD

  const { register, handleSubmit, formState: { errors } } = useForm({
    resolver: zodResolver(schema),
  })

  const { mutate, isPending, error } = useMutation({
    mutationFn: authService.login,
    onSuccess: (res) => {
      setAuth(res.data.data.user, res.data.data.token)
      navigate(from, { replace: true })
    },
  })

  return (
    <div className="min-h-screen bg-gradient-to-br from-brand-dark via-brand to-primary-400 flex items-center justify-center p-4">
      <FadeIn className="w-full max-w-md bg-white rounded-2xl shadow-2xl p-8">
        <div className="text-center mb-8">
          <div className="w-14 h-14 bg-brand rounded-2xl flex items-center justify-center text-white font-bold text-xl mx-auto mb-4">
            AG
          </div>
          <h1 className="text-2xl font-bold text-gray-900">Al-Ghani ERP</h1>
          <p className="text-sm text-gray-500 mt-1">Sign in to your account</p>
        </div>

        {error && (
          <Alert
            type="error"
            message={error.response?.data?.message || 'Login failed. Please try again.'}
            className="mb-6"
          />
        )}

        <form onSubmit={handleSubmit(mutate)} className="space-y-5">
          <Input
            label="Email"
            type="email"
            placeholder="admin@alghani.com"
            required
            error={errors.email?.message}
            {...register('email')}
          />
          <Input
            label="Password"
            type="password"
            placeholder="••••••••"
            required
            error={errors.password?.message}
            {...register('password')}
          />
          <Button type="submit" loading={isPending} className="w-full" size="lg">
            Sign In
          </Button>
        </form>
      </FadeIn>
    </div>
  )
}
