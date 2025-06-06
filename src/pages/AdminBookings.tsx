
import { useState } from 'react'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Badge } from "@/components/ui/badge"
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle } from "@/components/ui/dialog"
import { Textarea } from "@/components/ui/textarea"
import { Search, Calendar, Clock, User, MapPin, CheckCircle, XCircle, Edit } from "lucide-react"

interface Booking {
  id: number
  userId: number
  userName: string
  userEmail: string
  arenaName: string
  date: string
  startTime: string
  endTime: string
  duration: number
  totalPrice: number
  status: 'pending' | 'confirmed' | 'cancelled' | 'completed'
  paymentStatus: 'pending' | 'paid'
  createdAt: string
}

export default function AdminBookings() {
  const [bookings] = useState<Booking[]>([
    {
      id: 1,
      userId: 1,
      userName: "John Doe",
      userEmail: "john@example.com",
      arenaName: "Lapangan A - Indoor",
      date: "2024-01-15",
      startTime: "14:00",
      endTime: "16:00",
      duration: 2,
      totalPrice: 300000,
      status: "pending",
      paymentStatus: "pending",
      createdAt: "2024-01-10 10:30"
    },
    {
      id: 2,
      userId: 2,
      userName: "Jane Smith",
      userEmail: "jane@example.com",
      arenaName: "Lapangan B - Outdoor",
      date: "2024-01-16",
      startTime: "16:00",
      endTime: "18:00",
      duration: 2,
      totalPrice: 240000,
      status: "confirmed",
      paymentStatus: "paid",
      createdAt: "2024-01-11 14:20"
    },
    {
      id: 3,
      userId: 3,
      userName: "Mike Johnson",
      userEmail: "mike@example.com",
      arenaName: "Lapangan A - Indoor",
      date: "2024-01-17",
      startTime: "09:00",
      endTime: "11:00",
      duration: 2,
      totalPrice: 300000,
      status: "completed",
      paymentStatus: "paid",
      createdAt: "2024-01-12 09:15"
    }
  ])

  const [searchTerm, setSearchTerm] = useState("")
  const [statusFilter, setStatusFilter] = useState("all")
  const [selectedBooking, setSelectedBooking] = useState<Booking | null>(null)
  const [isDetailOpen, setIsDetailOpen] = useState(false)
  const [actionType, setActionType] = useState<'confirm' | 'cancel' | 'edit' | null>(null)
  const [actionNote, setActionNote] = useState("")

  const filteredBookings = bookings.filter(booking => {
    const matchesSearch = booking.userName.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         booking.arenaName.toLowerCase().includes(searchTerm.toLowerCase())
    const matchesStatus = statusFilter === "all" || booking.status === statusFilter
    return matchesSearch && matchesStatus
  })

  const handleBookingAction = (booking: Booking, action: 'confirm' | 'cancel' | 'edit') => {
    setSelectedBooking(booking)
    setActionType(action)
    setActionNote("")
    setIsDetailOpen(true)
  }

  const executeAction = () => {
    if (!selectedBooking || !actionType) return
    
    // Here you would make API call to update booking
    console.log(`${actionType} booking ${selectedBooking.id}`, actionNote)
    setIsDetailOpen(false)
  }

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'pending': return 'secondary'
      case 'confirmed': return 'default'
      case 'cancelled': return 'destructive'
      case 'completed': return 'outline'
      default: return 'secondary'
    }
  }

  const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0
    }).format(amount)
  }

  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
      weekday: 'long',
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    })
  }

  return (
    <div className="p-6 space-y-6">
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 className="text-3xl font-bold">Kelola Booking</h1>
          <p className="text-muted-foreground">Kelola semua booking pelanggan</p>
        </div>
      </div>

      {/* Filters */}
      <div className="flex flex-col md:flex-row gap-4">
        <div className="flex items-center space-x-2 flex-1">
          <Search className="h-4 w-4 text-muted-foreground" />
          <Input
            placeholder="Cari booking berdasarkan nama atau lapangan..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
          />
        </div>
        <Select value={statusFilter} onValueChange={setStatusFilter}>
          <SelectTrigger className="w-full md:w-48">
            <SelectValue placeholder="Filter Status" />
          </SelectTrigger>
          <SelectContent>
            <SelectItem value="all">Semua Status</SelectItem>
            <SelectItem value="pending">Pending</SelectItem>
            <SelectItem value="confirmed">Confirmed</SelectItem>
            <SelectItem value="cancelled">Cancelled</SelectItem>
            <SelectItem value="completed">Completed</SelectItem>
          </SelectContent>
        </Select>
      </div>

      {/* Bookings List */}
      <div className="grid gap-4">
        {filteredBookings.map((booking) => (
          <Card key={booking.id}>
            <CardContent className="p-6">
              <div className="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div className="space-y-2">
                  <div className="flex items-center gap-2">
                    <h3 className="font-semibold text-lg">#{booking.id}</h3>
                    <Badge variant={getStatusColor(booking.status)}>
                      {booking.status}
                    </Badge>
                    {booking.paymentStatus === 'paid' && (
                      <Badge variant="outline" className="text-green-600">
                        Paid
                      </Badge>
                    )}
                  </div>
                  
                  <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-2 text-sm text-muted-foreground">
                    <div className="flex items-center">
                      <User className="h-4 w-4 mr-1" />
                      {booking.userName}
                    </div>
                    <div className="flex items-center">
                      <MapPin className="h-4 w-4 mr-1" />
                      {booking.arenaName}
                    </div>
                    <div className="flex items-center">
                      <Calendar className="h-4 w-4 mr-1" />
                      {formatDate(booking.date)}
                    </div>
                    <div className="flex items-center">
                      <Clock className="h-4 w-4 mr-1" />
                      {booking.startTime} - {booking.endTime}
                    </div>
                  </div>
                  
                  <div className="text-lg font-semibold">
                    {formatCurrency(booking.totalPrice)}
                  </div>
                </div>
                
                <div className="flex flex-wrap gap-2">
                  {booking.status === 'pending' && (
                    <>
                      <Button
                        size="sm"
                        onClick={() => handleBookingAction(booking, 'confirm')}
                      >
                        <CheckCircle className="h-4 w-4 mr-1" />
                        Konfirmasi
                      </Button>
                      <Button
                        variant="outline"
                        size="sm"
                        onClick={() => handleBookingAction(booking, 'cancel')}
                      >
                        <XCircle className="h-4 w-4 mr-1" />
                        Batalkan
                      </Button>
                    </>
                  )}
                  <Button
                    variant="outline"
                    size="sm"
                    onClick={() => handleBookingAction(booking, 'edit')}
                  >
                    <Edit className="h-4 w-4 mr-1" />
                    Edit
                  </Button>
                </div>
              </div>
            </CardContent>
          </Card>
        ))}
      </div>

      {/* Action Dialog */}
      <Dialog open={isDetailOpen} onOpenChange={setIsDetailOpen}>
        <DialogContent>
          <DialogHeader>
            <DialogTitle>
              {actionType === 'confirm' && 'Konfirmasi Booking'}
              {actionType === 'cancel' && 'Batalkan Booking'}
              {actionType === 'edit' && 'Edit Booking'}
            </DialogTitle>
            <DialogDescription>
              {selectedBooking && (
                <>Booking #{selectedBooking.id} - {selectedBooking.userName}</>
              )}
            </DialogDescription>
          </DialogHeader>
          
          {selectedBooking && (
            <div className="space-y-4">
              <div className="grid grid-cols-2 gap-4 text-sm">
                <div>
                  <span className="font-medium">Lapangan:</span>
                  <p>{selectedBooking.arenaName}</p>
                </div>
                <div>
                  <span className="font-medium">Tanggal:</span>
                  <p>{formatDate(selectedBooking.date)}</p>
                </div>
                <div>
                  <span className="font-medium">Waktu:</span>
                  <p>{selectedBooking.startTime} - {selectedBooking.endTime}</p>
                </div>
                <div>
                  <span className="font-medium">Total:</span>
                  <p>{formatCurrency(selectedBooking.totalPrice)}</p>
                </div>
              </div>
              
              <div>
                <label className="text-sm font-medium">
                  {actionType === 'confirm' && 'Catatan Konfirmasi'}
                  {actionType === 'cancel' && 'Alasan Pembatalan'}
                  {actionType === 'edit' && 'Catatan Perubahan'}
                </label>
                <Textarea
                  value={actionNote}
                  onChange={(e) => setActionNote(e.target.value)}
                  placeholder="Masukkan catatan..."
                  className="mt-1"
                />
              </div>
              
              <div className="flex justify-end space-x-2">
                <Button variant="outline" onClick={() => setIsDetailOpen(false)}>
                  Batal
                </Button>
                <Button onClick={executeAction}>
                  {actionType === 'confirm' && 'Konfirmasi'}
                  {actionType === 'cancel' && 'Batalkan'}
                  {actionType === 'edit' && 'Simpan'}
                </Button>
              </div>
            </div>
          )}
        </DialogContent>
      </Dialog>
    </div>
  )
}
