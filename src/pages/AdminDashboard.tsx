
import { useState, useEffect } from 'react'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Badge } from "@/components/ui/badge"
import { Calendar, Users, MapPin, DollarSign, TrendingUp, Bell } from "lucide-react"

export default function AdminDashboard() {
  const [stats, setStats] = useState({
    totalUsers: 0,
    totalArenas: 0,
    upcomingBookings: 0,
    totalRevenue: 0
  })

  const [notifications] = useState([
    { id: 1, type: 'booking', message: 'Booking baru perlu konfirmasi - Lapangan A', time: '2 menit lalu' },
    { id: 2, type: 'cancel', message: 'Pembatalan booking - Lapangan B', time: '1 jam lalu' },
    { id: 3, type: 'payment', message: 'Pembayaran diterima - Booking #123', time: '3 jam lalu' }
  ])

  const [recentBookings] = useState([
    { id: 1, user: 'John Doe', arena: 'Lapangan A', date: '2024-01-15', time: '14:00-16:00', status: 'confirmed' },
    { id: 2, user: 'Jane Smith', arena: 'Lapangan B', date: '2024-01-15', time: '16:00-18:00', status: 'pending' },
    { id: 3, user: 'Mike Johnson', arena: 'Lapangan C', date: '2024-01-16', time: '09:00-11:00', status: 'confirmed' }
  ])

  useEffect(() => {
    // Simulate API call to get dashboard stats
    setStats({
      totalUsers: 156,
      totalArenas: 8,
      upcomingBookings: 24,
      totalRevenue: 15750000
    })
  }, [])

  const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0
    }).format(amount)
  }

  return (
    <div className="p-6 space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-bold">Dashboard Admin</h1>
          <p className="text-muted-foreground">Selamat datang kembali, Admin!</p>
        </div>
        <div className="flex items-center space-x-2">
          <Button variant="outline" size="sm">
            <Bell className="h-4 w-4 mr-2" />
            Notifikasi ({notifications.length})
          </Button>
        </div>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Total User</CardTitle>
            <Users className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">{stats.totalUsers}</div>
            <p className="text-xs text-muted-foreground">+12% dari bulan lalu</p>
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Total Lapangan</CardTitle>
            <MapPin className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">{stats.totalArenas}</div>
            <p className="text-xs text-muted-foreground">2 lapangan aktif</p>
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Booking Aktif</CardTitle>
            <Calendar className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">{stats.upcomingBookings}</div>
            <p className="text-xs text-muted-foreground">Minggu ini</p>
          </CardContent>
        </Card>

        <Card>
          <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle className="text-sm font-medium">Pendapatan</CardTitle>
            <DollarSign className="h-4 w-4 text-muted-foreground" />
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">{formatCurrency(stats.totalRevenue)}</div>
            <p className="text-xs text-muted-foreground">+8% dari bulan lalu</p>
          </CardContent>
        </Card>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Notifications */}
        <Card>
          <CardHeader>
            <CardTitle className="flex items-center">
              <Bell className="h-5 w-5 mr-2" />
              Notifikasi Terbaru
            </CardTitle>
            <CardDescription>
              Aktivitas yang memerlukan perhatian Anda
            </CardDescription>
          </CardHeader>
          <CardContent className="space-y-4">
            {notifications.map((notification) => (
              <div key={notification.id} className="flex items-start space-x-3 p-3 rounded-lg border">
                <div className="flex-1">
                  <p className="text-sm font-medium">{notification.message}</p>
                  <p className="text-xs text-muted-foreground">{notification.time}</p>
                </div>
                <Badge variant={notification.type === 'booking' ? 'default' : 'secondary'}>
                  {notification.type}
                </Badge>
              </div>
            ))}
          </CardContent>
        </Card>

        {/* Recent Bookings */}
        <Card>
          <CardHeader>
            <CardTitle className="flex items-center">
              <Calendar className="h-5 w-5 mr-2" />
              Booking Terbaru
            </CardTitle>
            <CardDescription>
              Daftar booking yang baru masuk
            </CardDescription>
          </CardHeader>
          <CardContent>
            <div className="space-y-3">
              {recentBookings.map((booking) => (
                <div key={booking.id} className="flex items-center justify-between p-3 rounded-lg border">
                  <div>
                    <p className="font-medium">{booking.user}</p>
                    <p className="text-sm text-muted-foreground">{booking.arena}</p>
                    <p className="text-xs text-muted-foreground">{booking.date} • {booking.time}</p>
                  </div>
                  <Badge variant={booking.status === 'confirmed' ? 'default' : 'secondary'}>
                    {booking.status}
                  </Badge>
                </div>
              ))}
            </div>
          </CardContent>
        </Card>
      </div>

      {/* Revenue Chart Placeholder */}
      <Card>
        <CardHeader>
          <CardTitle className="flex items-center">
            <TrendingUp className="h-5 w-5 mr-2" />
            Grafik Pendapatan
          </CardTitle>
          <CardDescription>
            Pendapatan booking per bulan
          </CardDescription>
        </CardHeader>
        <CardContent>
          <div className="h-64 flex items-center justify-center border-2 border-dashed rounded-lg">
            <p className="text-muted-foreground">Grafik akan ditampilkan di sini</p>
          </div>
        </CardContent>
      </Card>
    </div>
  )
}
