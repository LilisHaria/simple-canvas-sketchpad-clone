
import { useState } from 'react'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Badge } from "@/components/ui/badge"
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select"
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle } from "@/components/ui/dialog"
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar"
import { Search, User, Mail, Phone, Calendar, UserX, UserCheck } from "lucide-react"

interface User {
  id: number
  fullName: string
  username: string
  email: string
  phoneNumber: string
  role: 'user' | 'admin'
  isActive: boolean
  registrationDate: string
  lastLogin: string
  totalBookings: number
  totalSpent: number
}

export default function AdminUsers() {
  const [users] = useState<User[]>([
    {
      id: 1,
      fullName: "John Doe",
      username: "johndoe",
      email: "john@example.com",
      phoneNumber: "081234567890",
      role: "user",
      isActive: true,
      registrationDate: "2024-01-01",
      lastLogin: "2024-01-14",
      totalBookings: 5,
      totalSpent: 750000
    },
    {
      id: 2,
      fullName: "Jane Smith",
      username: "janesmith",
      email: "jane@example.com",
      phoneNumber: "081234567891",
      role: "user",
      isActive: true,
      registrationDate: "2024-01-05",
      lastLogin: "2024-01-13",
      totalBookings: 3,
      totalSpent: 450000
    },
    {
      id: 3,
      fullName: "Mike Johnson",
      username: "mikejohnson",
      email: "mike@example.com",
      phoneNumber: "081234567892",
      role: "user",
      isActive: false,
      registrationDate: "2024-01-10",
      lastLogin: "2024-01-12",
      totalBookings: 1,
      totalSpent: 150000
    }
  ])

  const [searchTerm, setSearchTerm] = useState("")
  const [statusFilter, setStatusFilter] = useState("all")
  const [selectedUser, setSelectedUser] = useState<User | null>(null)
  const [isDetailOpen, setIsDetailOpen] = useState(false)

  const filteredUsers = users.filter(user => {
    const matchesSearch = user.fullName.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         user.email.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         user.username.toLowerCase().includes(searchTerm.toLowerCase())
    const matchesStatus = statusFilter === "all" || 
                         (statusFilter === "active" && user.isActive) ||
                         (statusFilter === "inactive" && !user.isActive)
    return matchesSearch && matchesStatus
  })

  const handleUserDetail = (user: User) => {
    setSelectedUser(user)
    setIsDetailOpen(true)
  }

  const toggleUserStatus = (userId: number) => {
    // Here you would make API call to toggle user status
    console.log(`Toggle user ${userId} status`)
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
      day: 'numeric',
      month: 'long',
      year: 'numeric'
    })
  }

  const getInitials = (name: string) => {
    return name.split(' ').map(n => n[0]).join('').toUpperCase()
  }

  return (
    <div className="p-6 space-y-6">
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 className="text-3xl font-bold">Kelola User</h1>
          <p className="text-muted-foreground">Kelola semua pengguna terdaftar</p>
        </div>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
        <Card>
          <CardHeader className="pb-2">
            <CardTitle className="text-sm font-medium">Total User</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">{users.length}</div>
            <p className="text-xs text-muted-foreground">Pengguna terdaftar</p>
          </CardContent>
        </Card>
        <Card>
          <CardHeader className="pb-2">
            <CardTitle className="text-sm font-medium">User Aktif</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">
              {users.filter(u => u.isActive).length}
            </div>
            <p className="text-xs text-muted-foreground">Pengguna aktif</p>
          </CardContent>
        </Card>
        <Card>
          <CardHeader className="pb-2">
            <CardTitle className="text-sm font-medium">Total Booking</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="text-2xl font-bold">
              {users.reduce((sum, user) => sum + user.totalBookings, 0)}
            </div>
            <p className="text-xs text-muted-foreground">Booking keseluruhan</p>
          </CardContent>
        </Card>
      </div>

      {/* Filters */}
      <div className="flex flex-col md:flex-row gap-4">
        <div className="flex items-center space-x-2 flex-1">
          <Search className="h-4 w-4 text-muted-foreground" />
          <Input
            placeholder="Cari user berdasarkan nama, email, atau username..."
            value={searchTerm}
            onChange={(e) => setSearchTerm(e.target.value)}
          />
        </div>
        <Select value={statusFilter} onValueChange={setStatusFilter}>
          <SelectTrigger className="w-full md:w-48">
            <SelectValue placeholder="Filter Status" />
          </SelectTrigger>
          <SelectContent>
            <SelectItem value="all">Semua User</SelectItem>
            <SelectItem value="active">User Aktif</SelectItem>
            <SelectItem value="inactive">User Non-aktif</SelectItem>
          </SelectContent>
        </Select>
      </div>

      {/* Users List */}
      <div className="grid gap-4">
        {filteredUsers.map((user) => (
          <Card key={user.id} className="cursor-pointer hover:shadow-md transition-shadow">
            <CardContent className="p-6">
              <div className="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div className="flex items-start space-x-4">
                  <Avatar className="h-12 w-12">
                    <AvatarImage src={`/placeholder.svg`} />
                    <AvatarFallback>{getInitials(user.fullName)}</AvatarFallback>
                  </Avatar>
                  
                  <div className="space-y-2">
                    <div className="flex items-center gap-2">
                      <h3 className="font-semibold text-lg">{user.fullName}</h3>
                      <Badge variant={user.isActive ? "default" : "secondary"}>
                        {user.isActive ? "Aktif" : "Non-aktif"}
                      </Badge>
                      {user.role === 'admin' && (
                        <Badge variant="outline">Admin</Badge>
                      )}
                    </div>
                    
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 text-sm text-muted-foreground">
                      <div className="flex items-center">
                        <User className="h-4 w-4 mr-1" />
                        @{user.username}
                      </div>
                      <div className="flex items-center">
                        <Mail className="h-4 w-4 mr-1" />
                        {user.email}
                      </div>
                      <div className="flex items-center">
                        <Phone className="h-4 w-4 mr-1" />
                        {user.phoneNumber}
                      </div>
                    </div>
                    
                    <div className="flex flex-wrap gap-4 text-sm">
                      <span><strong>{user.totalBookings}</strong> booking</span>
                      <span><strong>{formatCurrency(user.totalSpent)}</strong> total</span>
                      <span>Bergabung: {formatDate(user.registrationDate)}</span>
                    </div>
                  </div>
                </div>
                
                <div className="flex flex-wrap gap-2">
                  <Button
                    variant="outline"
                    size="sm"
                    onClick={() => handleUserDetail(user)}
                  >
                    Detail
                  </Button>
                  <Button
                    variant={user.isActive ? "outline" : "default"}
                    size="sm"
                    onClick={() => toggleUserStatus(user.id)}
                  >
                    {user.isActive ? (
                      <>
                        <UserX className="h-4 w-4 mr-1" />
                        Blokir
                      </>
                    ) : (
                      <>
                        <UserCheck className="h-4 w-4 mr-1" />
                        Aktifkan
                      </>
                    )}
                  </Button>
                </div>
              </div>
            </CardContent>
          </Card>
        ))}
      </div>

      {/* User Detail Dialog */}
      <Dialog open={isDetailOpen} onOpenChange={setIsDetailOpen}>
        <DialogContent className="max-w-2xl">
          <DialogHeader>
            <DialogTitle>Detail User</DialogTitle>
            <DialogDescription>
              Informasi lengkap pengguna
            </DialogDescription>
          </DialogHeader>
          
          {selectedUser && (
            <div className="space-y-6">
              <div className="flex items-center space-x-4">
                <Avatar className="h-16 w-16">
                  <AvatarImage src={`/placeholder.svg`} />
                  <AvatarFallback>{getInitials(selectedUser.fullName)}</AvatarFallback>
                </Avatar>
                <div>
                  <h3 className="text-xl font-semibold">{selectedUser.fullName}</h3>
                  <p className="text-muted-foreground">@{selectedUser.username}</p>
                </div>
              </div>
              
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <h4 className="font-medium mb-2">Informasi Kontak</h4>
                  <div className="space-y-2 text-sm">
                    <div className="flex items-center">
                      <Mail className="h-4 w-4 mr-2" />
                      {selectedUser.email}
                    </div>
                    <div className="flex items-center">
                      <Phone className="h-4 w-4 mr-2" />
                      {selectedUser.phoneNumber}
                    </div>
                  </div>
                </div>
                
                <div>
                  <h4 className="font-medium mb-2">Status Akun</h4>
                  <div className="space-y-2 text-sm">
                    <div className="flex justify-between">
                      <span>Status:</span>
                      <Badge variant={selectedUser.isActive ? "default" : "secondary"}>
                        {selectedUser.isActive ? "Aktif" : "Non-aktif"}
                      </Badge>
                    </div>
                    <div className="flex justify-between">
                      <span>Role:</span>
                      <Badge variant="outline">{selectedUser.role}</Badge>
                    </div>
                  </div>
                </div>
                
                <div>
                  <h4 className="font-medium mb-2">Aktivitas</h4>
                  <div className="space-y-2 text-sm">
                    <div className="flex justify-between">
                      <span>Total Booking:</span>
                      <span className="font-medium">{selectedUser.totalBookings}</span>
                    </div>
                    <div className="flex justify-between">
                      <span>Total Spent:</span>
                      <span className="font-medium">{formatCurrency(selectedUser.totalSpent)}</span>
                    </div>
                  </div>
                </div>
                
                <div>
                  <h4 className="font-medium mb-2">Tanggal Penting</h4>
                  <div className="space-y-2 text-sm">
                    <div className="flex justify-between">
                      <span>Bergabung:</span>
                      <span>{formatDate(selectedUser.registrationDate)}</span>
                    </div>
                    <div className="flex justify-between">
                      <span>Login Terakhir:</span>
                      <span>{formatDate(selectedUser.lastLogin)}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          )}
        </DialogContent>
      </Dialog>
    </div>
  )
}
