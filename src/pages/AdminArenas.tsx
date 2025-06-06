
import { useState } from 'react'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Badge } from "@/components/ui/badge"
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from "@/components/ui/dialog"
import { Textarea } from "@/components/ui/textarea"
import { Switch } from "@/components/ui/switch"
import { Plus, Search, Edit, Trash2, MapPin, Clock, DollarSign } from "lucide-react"

interface Arena {
  id: number
  name: string
  type: string
  location: string
  pricePerHour: number
  description: string
  isActive: boolean
  operatingHours: string
  image: string
}

export default function AdminArenas() {
  const [arenas, setArenas] = useState<Arena[]>([
    {
      id: 1,
      name: "Lapangan A - Indoor",
      type: "Indoor",
      location: "Lantai 1",
      pricePerHour: 150000,
      description: "Lapangan futsal indoor dengan AC dan pencahayaan LED",
      isActive: true,
      operatingHours: "06:00 - 23:00",
      image: "/placeholder.svg"
    },
    {
      id: 2,
      name: "Lapangan B - Outdoor",
      type: "Outdoor",
      location: "Area Belakang",
      pricePerHour: 120000,
      description: "Lapangan outdoor dengan rumput sintentis berkualitas tinggi",
      isActive: true,
      operatingHours: "06:00 - 22:00",
      image: "/placeholder.svg"
    }
  ])

  const [searchTerm, setSearchTerm] = useState("")
  const [isDialogOpen, setIsDialogOpen] = useState(false)
  const [editingArena, setEditingArena] = useState<Arena | null>(null)
  const [formData, setFormData] = useState<Partial<Arena>>({})

  const filteredArenas = arenas.filter(arena =>
    arena.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
    arena.type.toLowerCase().includes(searchTerm.toLowerCase())
  )

  const handleAddArena = () => {
    setEditingArena(null)
    setFormData({
      name: "",
      type: "",
      location: "",
      pricePerHour: 0,
      description: "",
      isActive: true,
      operatingHours: "",
      image: "/placeholder.svg"
    })
    setIsDialogOpen(true)
  }

  const handleEditArena = (arena: Arena) => {
    setEditingArena(arena)
    setFormData(arena)
    setIsDialogOpen(true)
  }

  const handleSaveArena = () => {
    if (editingArena) {
      setArenas(arenas.map(arena => 
        arena.id === editingArena.id ? { ...arena, ...formData } : arena
      ))
    } else {
      const newArena: Arena = {
        id: Math.max(...arenas.map(a => a.id)) + 1,
        ...formData as Arena
      }
      setArenas([...arenas, newArena])
    }
    setIsDialogOpen(false)
  }

  const handleDeleteArena = (id: number) => {
    if (confirm('Apakah Anda yakin ingin menghapus lapangan ini?')) {
      setArenas(arenas.filter(arena => arena.id !== id))
    }
  }

  const toggleArenaStatus = (id: number) => {
    setArenas(arenas.map(arena =>
      arena.id === id ? { ...arena, isActive: !arena.isActive } : arena
    ))
  }

  const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0
    }).format(amount)
  }

  return (
    <div className="p-6 space-y-6">
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 className="text-3xl font-bold">Kelola Lapangan</h1>
          <p className="text-muted-foreground">Kelola semua lapangan futsal</p>
        </div>
        <Button onClick={handleAddArena}>
          <Plus className="h-4 w-4 mr-2" />
          Tambah Lapangan
        </Button>
      </div>

      {/* Search */}
      <div className="flex items-center space-x-2">
        <Search className="h-4 w-4 text-muted-foreground" />
        <Input
          placeholder="Cari lapangan..."
          value={searchTerm}
          onChange={(e) => setSearchTerm(e.target.value)}
          className="max-w-sm"
        />
      </div>

      {/* Arena Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {filteredArenas.map((arena) => (
          <Card key={arena.id} className="overflow-hidden">
            <div className="aspect-video bg-muted relative">
              <img 
                src={arena.image} 
                alt={arena.name}
                className="w-full h-full object-cover"
              />
              <Badge 
                className="absolute top-2 right-2"
                variant={arena.isActive ? "default" : "secondary"}
              >
                {arena.isActive ? "Aktif" : "Non-aktif"}
              </Badge>
            </div>
            <CardHeader>
              <CardTitle className="text-lg">{arena.name}</CardTitle>
              <CardDescription>{arena.description}</CardDescription>
            </CardHeader>
            <CardContent className="space-y-3">
              <div className="flex items-center text-sm text-muted-foreground">
                <MapPin className="h-4 w-4 mr-1" />
                {arena.location}
              </div>
              <div className="flex items-center text-sm text-muted-foreground">
                <Clock className="h-4 w-4 mr-1" />
                {arena.operatingHours}
              </div>
              <div className="flex items-center text-sm font-medium">
                <DollarSign className="h-4 w-4 mr-1" />
                {formatCurrency(arena.pricePerHour)}/jam
              </div>
              
              <div className="flex items-center justify-between pt-2">
                <div className="flex items-center space-x-2">
                  <Switch
                    checked={arena.isActive}
                    onCheckedChange={() => toggleArenaStatus(arena.id)}
                  />
                  <span className="text-sm">Aktif</span>
                </div>
                <div className="flex space-x-2">
                  <Button
                    variant="outline"
                    size="sm"
                    onClick={() => handleEditArena(arena)}
                  >
                    <Edit className="h-4 w-4" />
                  </Button>
                  <Button
                    variant="outline"
                    size="sm"
                    onClick={() => handleDeleteArena(arena.id)}
                  >
                    <Trash2 className="h-4 w-4" />
                  </Button>
                </div>
              </div>
            </CardContent>
          </Card>
        ))}
      </div>

      {/* Add/Edit Dialog */}
      <Dialog open={isDialogOpen} onOpenChange={setIsDialogOpen}>
        <DialogContent className="max-w-md">
          <DialogHeader>
            <DialogTitle>
              {editingArena ? 'Edit Lapangan' : 'Tambah Lapangan Baru'}
            </DialogTitle>
            <DialogDescription>
              {editingArena ? 'Update informasi lapangan' : 'Tambahkan lapangan futsal baru'}
            </DialogDescription>
          </DialogHeader>
          
          <div className="space-y-4">
            <div>
              <Label htmlFor="name">Nama Lapangan</Label>
              <Input
                id="name"
                value={formData.name || ""}
                onChange={(e) => setFormData({...formData, name: e.target.value})}
                placeholder="Contoh: Lapangan A - Indoor"
              />
            </div>
            
            <div>
              <Label htmlFor="type">Tipe</Label>
              <Input
                id="type"
                value={formData.type || ""}
                onChange={(e) => setFormData({...formData, type: e.target.value})}
                placeholder="Indoor/Outdoor/Synthetic"
              />
            </div>
            
            <div>
              <Label htmlFor="location">Lokasi</Label>
              <Input
                id="location"
                value={formData.location || ""}
                onChange={(e) => setFormData({...formData, location: e.target.value})}
                placeholder="Contoh: Lantai 1"
              />
            </div>
            
            <div>
              <Label htmlFor="price">Harga per Jam (Rp)</Label>
              <Input
                id="price"
                type="number"
                value={formData.pricePerHour || ""}
                onChange={(e) => setFormData({...formData, pricePerHour: parseInt(e.target.value)})}
                placeholder="150000"
              />
            </div>
            
            <div>
              <Label htmlFor="hours">Jam Operasional</Label>
              <Input
                id="hours"
                value={formData.operatingHours || ""}
                onChange={(e) => setFormData({...formData, operatingHours: e.target.value})}
                placeholder="06:00 - 23:00"
              />
            </div>
            
            <div>
              <Label htmlFor="description">Deskripsi</Label>
              <Textarea
                id="description"
                value={formData.description || ""}
                onChange={(e) => setFormData({...formData, description: e.target.value})}
                placeholder="Deskripsi lapangan..."
              />
            </div>
            
            <div className="flex items-center space-x-2">
              <Switch
                checked={formData.isActive || false}
                onCheckedChange={(checked) => setFormData({...formData, isActive: checked})}
              />
              <Label>Lapangan Aktif</Label>
            </div>
            
            <div className="flex justify-end space-x-2 pt-4">
              <Button variant="outline" onClick={() => setIsDialogOpen(false)}>
                Batal
              </Button>
              <Button onClick={handleSaveArena}>
                {editingArena ? 'Update' : 'Simpan'}
              </Button>
            </div>
          </div>
        </DialogContent>
      </Dialog>
    </div>
  )
}
