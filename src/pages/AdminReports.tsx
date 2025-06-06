
import { useState } from 'react'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"
import { Badge } from "@/components/ui/badge"
import { Calendar, Download, TrendingUp, TrendingDown, DollarSign, Users, MapPin, Clock } from "lucide-react"

export default function AdminReports() {
  const [reportType, setReportType] = useState("revenue")
  const [period, setPeriod] = useState("month")

  // Sample data - would come from API
  const revenueData = {
    total: 15750000,
    growth: 8.2,
    thisMonth: 5250000,
    lastMonth: 4850000,
    dailyAverage: 525000
  }

  const bookingData = {
    total: 156,
    growth: 12.5,
    thisMonth: 52,
    lastMonth: 46,
    confirmed: 48,
    pending: 4,
    cancelled: 2
  }

  const arenaPerformance = [
    { name: "Lapangan A - Indoor", bookings: 28, revenue: 4200000, utilization: 85 },
    { name: "Lapangan B - Outdoor", bookings: 24, revenue: 2880000, utilization: 70 },
    { name: "Lapangan C - Synthetic", bookings: 18, revenue: 3150000, utilization: 60 }
  ]

  const peakHours = [
    { hour: "16:00-17:00", bookings: 24 },
    { hour: "17:00-18:00", bookings: 22 },
    { hour: "18:00-19:00", bookings: 28 },
    { hour: "19:00-20:00", bookings: 26 },
    { hour: "20:00-21:00", bookings: 20 }
  ]

  const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0
    }).format(amount)
  }

  const exportReport = (type: string) => {
    // Here you would implement export functionality
    console.log(`Exporting ${type} report`)
  }

  return (
    <div className="p-6 space-y-6">
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 className="text-3xl font-bold">Laporan & Analisis</h1>
          <p className="text-muted-foreground">Analisis kinerja dan statistik bisnis</p>
        </div>
        <div className="flex gap-2">
          <Button variant="outline" onClick={() => exportReport('excel')}>
            <Download className="h-4 w-4 mr-2" />
            Export Excel
          </Button>
          <Button variant="outline" onClick={() => exportReport('pdf')}>
            <Download className="h-4 w-4 mr-2" />
            Export PDF
          </Button>
        </div>
      </div>

      {/* Report Filters */}
      <div className="flex flex-col md:flex-row gap-4">
        <Select value={reportType} onValueChange={setReportType}>
          <SelectTrigger className="w-full md:w-48">
            <SelectValue placeholder="Jenis Laporan" />
          </SelectTrigger>
          <SelectContent>
            <SelectItem value="revenue">Laporan Keuangan</SelectItem>
            <SelectItem value="booking">Laporan Booking</SelectItem>
            <SelectItem value="arena">Performa Lapangan</SelectItem>
            <SelectItem value="user">Analisis User</SelectItem>
          </SelectContent>
        </Select>
        
        <Select value={period} onValueChange={setPeriod}>
          <SelectTrigger className="w-full md:w-48">
            <SelectValue placeholder="Periode" />
          </SelectTrigger>
          <SelectContent>
            <SelectItem value="week">Minggu Ini</SelectItem>
            <SelectItem value="month">Bulan Ini</SelectItem>
            <SelectItem value="quarter">Kuartal Ini</SelectItem>
            <SelectItem value="year">Tahun Ini</SelectItem>
          </SelectContent>
        </Select>
      </div>

      {/* Revenue Report */}
      {reportType === 'revenue' && (
        <div className="space-y-6">
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <Card>
              <CardHeader className="pb-2">
                <CardTitle className="text-sm font-medium flex items-center">
                  <DollarSign className="h-4 w-4 mr-2" />
                  Total Pendapatan
                </CardTitle>
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">{formatCurrency(revenueData.total)}</div>
                <div className="flex items-center text-xs text-muted-foreground">
                  <TrendingUp className="h-3 w-3 mr-1 text-green-500" />
                  +{revenueData.growth}% dari bulan lalu
                </div>
              </CardContent>
            </Card>

            <Card>
              <CardHeader className="pb-2">
                <CardTitle className="text-sm font-medium">Bulan Ini</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">{formatCurrency(revenueData.thisMonth)}</div>
                <p className="text-xs text-muted-foreground">vs {formatCurrency(revenueData.lastMonth)} bulan lalu</p>
              </CardContent>
            </Card>

            <Card>
              <CardHeader className="pb-2">
                <CardTitle className="text-sm font-medium">Rata-rata Harian</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">{formatCurrency(revenueData.dailyAverage)}</div>
                <p className="text-xs text-muted-foreground">Per hari</p>
              </CardContent>
            </Card>

            <Card>
              <CardHeader className="pb-2">
                <CardTitle className="text-sm font-medium">Growth Rate</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold text-green-600">+{revenueData.growth}%</div>
                <p className="text-xs text-muted-foreground">Month over month</p>
              </CardContent>
            </Card>
          </div>

          <Card>
            <CardHeader>
              <CardTitle>Grafik Pendapatan Bulanan</CardTitle>
              <CardDescription>Pendapatan per bulan dalam 6 bulan terakhir</CardDescription>
            </CardHeader>
            <CardContent>
              <div className="h-64 flex items-center justify-center border-2 border-dashed rounded-lg">
                <p className="text-muted-foreground">Grafik pendapatan akan ditampilkan di sini</p>
              </div>
            </CardContent>
          </Card>
        </div>
      )}

      {/* Booking Report */}
      {reportType === 'booking' && (
        <div className="space-y-6">
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <Card>
              <CardHeader className="pb-2">
                <CardTitle className="text-sm font-medium flex items-center">
                  <Calendar className="h-4 w-4 mr-2" />
                  Total Booking
                </CardTitle>
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">{bookingData.total}</div>
                <div className="flex items-center text-xs text-muted-foreground">
                  <TrendingUp className="h-3 w-3 mr-1 text-green-500" />
                  +{bookingData.growth}% dari bulan lalu
                </div>
              </CardContent>
            </Card>

            <Card>
              <CardHeader className="pb-2">
                <CardTitle className="text-sm font-medium">Bulan Ini</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold">{bookingData.thisMonth}</div>
                <p className="text-xs text-muted-foreground">vs {bookingData.lastMonth} bulan lalu</p>
              </CardContent>
            </Card>

            <Card>
              <CardHeader className="pb-2">
                <CardTitle className="text-sm font-medium">Confirmed</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold text-green-600">{bookingData.confirmed}</div>
                <p className="text-xs text-muted-foreground">Booking terkonfirmasi</p>
              </CardContent>
            </Card>

            <Card>
              <CardHeader className="pb-2">
                <CardTitle className="text-sm font-medium">Pending</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="text-2xl font-bold text-yellow-600">{bookingData.pending}</div>
                <p className="text-xs text-muted-foreground">Menunggu konfirmasi</p>
              </CardContent>
            </Card>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <Card>
              <CardHeader>
                <CardTitle className="flex items-center">
                  <Clock className="h-5 w-5 mr-2" />
                  Jam Sibuk
                </CardTitle>
                <CardDescription>Distribusi booking per jam</CardDescription>
              </CardHeader>
              <CardContent>
                <div className="space-y-3">
                  {peakHours.map((hour, index) => (
                    <div key={index} className="flex items-center justify-between">
                      <span className="text-sm font-medium">{hour.hour}</span>
                      <div className="flex items-center space-x-2">
                        <div className="w-32 bg-gray-200 rounded-full h-2">
                          <div 
                            className="bg-blue-600 h-2 rounded-full" 
                            style={{ width: `${(hour.bookings / 30) * 100}%` }}
                          ></div>
                        </div>
                        <span className="text-sm text-muted-foreground">{hour.bookings}</span>
                      </div>
                    </div>
                  ))}
                </div>
              </CardContent>
            </Card>

            <Card>
              <CardHeader>
                <CardTitle>Status Booking</CardTitle>
                <CardDescription>Distribusi status booking bulan ini</CardDescription>
              </CardHeader>
              <CardContent>
                <div className="space-y-3">
                  <div className="flex justify-between items-center">
                    <span className="text-sm">Confirmed</span>
                    <div className="flex items-center space-x-2">
                      <Badge variant="default">{bookingData.confirmed}</Badge>
                      <span className="text-sm text-muted-foreground">
                        {Math.round((bookingData.confirmed / bookingData.thisMonth) * 100)}%
                      </span>
                    </div>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm">Pending</span>
                    <div className="flex items-center space-x-2">
                      <Badge variant="secondary">{bookingData.pending}</Badge>
                      <span className="text-sm text-muted-foreground">
                        {Math.round((bookingData.pending / bookingData.thisMonth) * 100)}%
                      </span>
                    </div>
                  </div>
                  <div className="flex justify-between items-center">
                    <span className="text-sm">Cancelled</span>
                    <div className="flex items-center space-x-2">
                      <Badge variant="destructive">{bookingData.cancelled}</Badge>
                      <span className="text-sm text-muted-foreground">
                        {Math.round((bookingData.cancelled / bookingData.thisMonth) * 100)}%
                      </span>
                    </div>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>
        </div>
      )}

      {/* Arena Performance */}
      {reportType === 'arena' && (
        <div className="space-y-6">
          <Card>
            <CardHeader>
              <CardTitle className="flex items-center">
                <MapPin className="h-5 w-5 mr-2" />
                Performa Lapangan
              </CardTitle>
              <CardDescription>Analisis kinerja setiap lapangan</CardDescription>
            </CardHeader>
            <CardContent>
              <div className="space-y-4">
                {arenaPerformance.map((arena, index) => (
                  <div key={index} className="p-4 border rounded-lg">
                    <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                      <div>
                        <h3 className="font-semibold">{arena.name}</h3>
                        <p className="text-sm text-muted-foreground">
                          {arena.bookings} booking • {formatCurrency(arena.revenue)}
                        </p>
                      </div>
                      <div className="flex items-center space-x-4">
                        <div className="text-center">
                          <div className="text-sm font-medium">Utilization</div>
                          <div className="text-lg font-bold text-blue-600">{arena.utilization}%</div>
                        </div>
                        <div className="w-24 bg-gray-200 rounded-full h-2">
                          <div 
                            className="bg-blue-600 h-2 rounded-full" 
                            style={{ width: `${arena.utilization}%` }}
                          ></div>
                        </div>
                      </div>
                    </div>
                  </div>
                ))}
              </div>
            </CardContent>
          </Card>
        </div>
      )}
    </div>
  )
}
