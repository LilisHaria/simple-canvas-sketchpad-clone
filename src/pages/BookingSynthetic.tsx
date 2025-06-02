
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { MapPin, Clock, Star, Zap, Settings } from 'lucide-react';

const BookingSynthetic = () => {
  return (
    <div className="min-h-screen bg-gray-50">
      {/* Header */}
      <div className="bg-white shadow-sm border-b">
        <div className="container mx-auto px-4 py-6">
          <div className="flex items-center space-x-3">
            <div className="p-2 bg-purple-100 rounded-lg">
              <Settings className="h-6 w-6 text-purple-600" />
            </div>
            <div>
              <h1 className="text-2xl font-bold text-gray-900">Arena Synthetic Modern</h1>
              <p className="text-gray-600">Lapangan futsal sintetis dengan standar profesional</p>
            </div>
          </div>
        </div>
      </div>

      {/* Content */}
      <div className="container mx-auto px-4 py-8">
        <div className="grid lg:grid-cols-2 gap-8">
          {/* Arena Details */}
          <div>
            <img 
              src="https://images.unsplash.com/photo-1517466787929-bc90951d0974?w=600&h=400&fit=crop" 
              alt="Arena Synthetic" 
              className="w-full h-64 object-cover rounded-lg mb-6"
            />
            
            <Card>
              <CardHeader>
                <CardTitle className="flex items-center space-x-2">
                  <Settings className="h-5 w-5 text-purple-600" />
                  <span>Fasilitas Arena Synthetic</span>
                </CardTitle>
                <CardDescription>
                  Teknologi rumput sintetis terdepan untuk performa maksimal
                </CardDescription>
              </CardHeader>
              <CardContent>
                <div className="space-y-4">
                  <div className="flex items-center space-x-3">
                    <Settings className="h-4 w-4 text-purple-600" />
                    <span>Rumput Sintetis FIFA Quality</span>
                    <Badge className="bg-purple-100 text-purple-800">Pro</Badge>
                  </div>
                  <div className="flex items-center space-x-3">
                    <Zap className="h-4 w-4 text-yellow-600" />
                    <span>Sistem Pencahayaan LED</span>
                  </div>
                  <div className="flex items-center space-x-3">
                    <Star className="h-4 w-4 text-blue-600" />
                    <span>Bantalan Shock Absorber</span>
                  </div>
                  <div className="flex items-center space-x-3">
                    <MapPin className="h-4 w-4 text-red-600" />
                    <span>Lokasi: Jakarta Pusat</span>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>

          {/* Booking Form */}
          <div>
            <Card>
              <CardHeader>
                <CardTitle className="flex items-center space-x-2">
                  <Clock className="h-5 w-5" />
                  <span>Booking Arena Synthetic</span>
                </CardTitle>
                <CardDescription>
                  Harga: Rp 180.000 per jam
                </CardDescription>
              </CardHeader>
              <CardContent>
                <div className="space-y-4">
                  <div>
                    <label className="block text-sm font-medium text-gray-700 mb-2">
                      Tanggal Booking
                    </label>
                    <input 
                      type="date" 
                      className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                    />
                  </div>
                  
                  <div>
                    <label className="block text-sm font-medium text-gray-700 mb-2">
                      Jam Mulai
                    </label>
                    <select className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                      <option>08:00</option>
                      <option>10:00</option>
                      <option>12:00</option>
                      <option>14:00</option>
                      <option>16:00</option>
                      <option>18:00</option>
                      <option>20:00</option>
                      <option>22:00</option>
                    </select>
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-gray-700 mb-2">
                      Durasi (Jam)
                    </label>
                    <select className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                      <option>1 Jam</option>
                      <option>2 Jam</option>
                      <option>3 Jam</option>
                      <option>4 Jam</option>
                    </select>
                  </div>

                  <div className="bg-gray-50 p-4 rounded-lg">
                    <div className="flex justify-between items-center">
                      <span className="font-medium">Total Harga:</span>
                      <span className="text-xl font-bold text-purple-600">Rp 180.000</span>
                    </div>
                  </div>

                  <Button className="w-full bg-purple-600 hover:bg-purple-700">
                    Book Sekarang
                  </Button>
                </div>
              </CardContent>
            </Card>
          </div>
        </div>
      </div>
    </div>
  );
};

export default BookingSynthetic;
