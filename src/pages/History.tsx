
import { useState, useEffect } from 'react';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { CalendarDays, Clock, MapPin, CreditCard } from 'lucide-react';

interface Booking {
  id: number;
  nama_lapangan: string;
  lokasi: string;
  tanggal_booking: string;
  jam_mulai: string;
  jam_selesai: string;
  status: 'confirmed' | 'pending' | 'cancelled';
  harga_per_jam: number;
}

const History = () => {
  const [bookings, setBookings] = useState<Booking[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // Mock data for demonstration
    const mockBookings: Booking[] = [
      {
        id: 1,
        nama_lapangan: "Arena Indoor Premium",
        lokasi: "Jakarta Selatan",
        tanggal_booking: "2024-06-01",
        jam_mulai: "14:00",
        jam_selesai: "16:00",
        status: "confirmed",
        harga_per_jam: 150000
      },
      {
        id: 2,
        nama_lapangan: "Arena Outdoor Sintetis",
        lokasi: "Jakarta Timur",
        tanggal_booking: "2024-05-25",
        jam_mulai: "10:00",
        jam_selesai: "12:00",
        status: "confirmed",
        harga_per_jam: 120000
      },
      {
        id: 3,
        nama_lapangan: "Arena Indoor AC",
        lokasi: "Jakarta Pusat",
        tanggal_booking: "2024-05-20",
        jam_mulai: "18:00",
        jam_selesai: "20:00",
        status: "cancelled",
        harga_per_jam: 180000
      }
    ];

    setTimeout(() => {
      setBookings(mockBookings);
      setLoading(false);
    }, 1000);
  }, []);

  const getStatusBadge = (status: string) => {
    switch (status) {
      case 'confirmed':
        return <Badge className="bg-green-100 text-green-800 hover:bg-green-100">Dikonfirmasi</Badge>;
      case 'pending':
        return <Badge className="bg-yellow-100 text-yellow-800 hover:bg-yellow-100">Menunggu</Badge>;
      case 'cancelled':
        return <Badge className="bg-red-100 text-red-800 hover:bg-red-100">Dibatalkan</Badge>;
      default:
        return <Badge variant="secondary">{status}</Badge>;
    }
  };

  const formatPrice = (price: number) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0
    }).format(price);
  };

  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
      weekday: 'long',
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
  };

  if (loading) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header */}
      <div className="bg-white shadow-sm border-b">
        <div className="container mx-auto px-4 py-6">
          <div className="flex items-center space-x-3">
            <div className="p-2 bg-primary/10 rounded-lg">
              <CalendarDays className="h-6 w-6 text-primary" />
            </div>
            <div>
              <h1 className="text-2xl font-bold text-gray-900">Riwayat Booking</h1>
              <p className="text-gray-600">Lihat semua riwayat pemesanan lapangan Anda</p>
            </div>
          </div>
        </div>
      </div>

      {/* Content */}
      <div className="container mx-auto px-4 py-8">
        <Card>
          <CardHeader>
            <CardTitle className="flex items-center space-x-2">
              <CalendarDays className="h-5 w-5" />
              <span>Riwayat Pemesanan</span>
            </CardTitle>
            <CardDescription>
              Total {bookings.length} pemesanan ditemukan
            </CardDescription>
          </CardHeader>
          <CardContent>
            {bookings.length === 0 ? (
              <div className="text-center py-12">
                <CalendarDays className="h-12 w-12 text-gray-400 mx-auto mb-4" />
                <h3 className="text-lg font-medium text-gray-900 mb-2">Belum Ada Riwayat</h3>
                <p className="text-gray-600">Anda belum melakukan pemesanan lapangan.</p>
              </div>
            ) : (
              <div className="overflow-x-auto">
                <Table>
                  <TableHeader>
                    <TableRow>
                      <TableHead className="w-[200px]">Lapangan</TableHead>
                      <TableHead>Lokasi</TableHead>
                      <TableHead>Tanggal</TableHead>
                      <TableHead>Waktu</TableHead>
                      <TableHead>Status</TableHead>
                      <TableHead className="text-right">Harga/Jam</TableHead>
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    {bookings.map((booking) => (
                      <TableRow key={booking.id} className="hover:bg-gray-50">
                        <TableCell className="font-medium">
                          <div>
                            <div className="font-semibold text-gray-900">{booking.nama_lapangan}</div>
                            <div className="text-sm text-gray-500">ID: #{booking.id}</div>
                          </div>
                        </TableCell>
                        <TableCell>
                          <div className="flex items-center space-x-2">
                            <MapPin className="h-4 w-4 text-gray-400" />
                            <span className="text-gray-700">{booking.lokasi}</span>
                          </div>
                        </TableCell>
                        <TableCell>
                          <div className="flex items-center space-x-2">
                            <CalendarDays className="h-4 w-4 text-gray-400" />
                            <span className="text-gray-700">{formatDate(booking.tanggal_booking)}</span>
                          </div>
                        </TableCell>
                        <TableCell>
                          <div className="flex items-center space-x-2">
                            <Clock className="h-4 w-4 text-gray-400" />
                            <span className="text-gray-700">{booking.jam_mulai} - {booking.jam_selesai}</span>
                          </div>
                        </TableCell>
                        <TableCell>
                          {getStatusBadge(booking.status)}
                        </TableCell>
                        <TableCell className="text-right">
                          <div className="flex items-center justify-end space-x-2">
                            <CreditCard className="h-4 w-4 text-gray-400" />
                            <span className="font-semibold text-gray-900">
                              {formatPrice(booking.harga_per_jam)}
                            </span>
                          </div>
                        </TableCell>
                      </TableRow>
                    ))}
                  </TableBody>
                </Table>
              </div>
            )}
          </CardContent>
        </Card>

        {/* Summary Card */}
        <div className="grid md:grid-cols-3 gap-6 mt-6">
          <Card>
            <CardContent className="p-6">
              <div className="flex items-center space-x-3">
                <div className="p-2 bg-green-100 rounded-lg">
                  <CalendarDays className="h-6 w-6 text-green-600" />
                </div>
                <div>
                  <p className="text-sm font-medium text-gray-600">Total Booking</p>
                  <p className="text-2xl font-bold text-gray-900">{bookings.length}</p>
                </div>
              </div>
            </CardContent>
          </Card>

          <Card>
            <CardContent className="p-6">
              <div className="flex items-center space-x-3">
                <div className="p-2 bg-blue-100 rounded-lg">
                  <Clock className="h-6 w-6 text-blue-600" />
                </div>
                <div>
                  <p className="text-sm font-medium text-gray-600">Jam Bermain</p>
                  <p className="text-2xl font-bold text-gray-900">
                    {bookings.reduce((total, booking) => {
                      const start = parseInt(booking.jam_mulai.split(':')[0]);
                      const end = parseInt(booking.jam_selesai.split(':')[0]);
                      return total + (end - start);
                    }, 0)} Jam
                  </p>
                </div>
              </div>
            </CardContent>
          </Card>

          <Card>
            <CardContent className="p-6">
              <div className="flex items-center space-x-3">
                <div className="p-2 bg-purple-100 rounded-lg">
                  <CreditCard className="h-6 w-6 text-purple-600" />
                </div>
                <div>
                  <p className="text-sm font-medium text-gray-600">Total Pengeluaran</p>
                  <p className="text-2xl font-bold text-gray-900">
                    {formatPrice(bookings.reduce((total, booking) => {
                      const start = parseInt(booking.jam_mulai.split(':')[0]);
                      const end = parseInt(booking.jam_selesai.split(':')[0]);
                      const hours = end - start;
                      return total + (booking.harga_per_jam * hours);
                    }, 0))}
                  </p>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>
      </div>
    </div>
  );
};

export default History;
