
import React from 'react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

const ERDDiagram = () => {
  return (
    <div className="p-6 bg-gray-50 min-h-screen">
      <div className="max-w-7xl mx-auto">
        <h1 className="text-3xl font-bold text-center mb-8 text-gray-800">
          Entity Relationship Diagram (ERD) - ArenaKuy
        </h1>
        
        <div className="relative">
          {/* SVG for relationship lines */}
          <svg className="absolute inset-0 w-full h-full pointer-events-none z-10" style={{ height: '800px' }}>
            {/* Relationship from pelanggan to booking */}
            <defs>
              <marker id="arrowhead" markerWidth="10" markerHeight="7" 
                refX="10" refY="3.5" orient="auto">
                <polygon points="0 0, 10 3.5, 0 7" fill="#666" />
              </marker>
            </defs>
            
            {/* Pelanggan to Booking */}
            <line x1="250" y1="200" x2="450" y2="350" stroke="#666" strokeWidth="2" markerEnd="url(#arrowhead)" />
            <text x="300" y="270" fill="#666" fontSize="12" fontWeight="bold">1</text>
            <text x="420" y="330" fill="#666" fontSize="12" fontWeight="bold">M</text>
            
            {/* Lapangan to Booking */}
            <line x1="750" y1="200" x2="550" y2="350" stroke="#666" strokeWidth="2" markerEnd="url(#arrowhead)" />
            <text x="700" y="270" fill="#666" fontSize="12" fontWeight="bold">1</text>
            <text x="570" y="330" fill="#666" fontSize="12" fontWeight="bold">M</text>
          </svg>
          
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8 relative z-20">
            {/* Entity Pelanggan */}
            <Card className="border-2 border-blue-500 shadow-lg">
              <CardHeader className="bg-blue-500 text-white">
                <CardTitle className="text-center">PELANGGAN</CardTitle>
              </CardHeader>
              <CardContent className="p-4">
                <div className="space-y-2">
                  <div className="flex items-center">
                    <span className="w-4 h-4 bg-yellow-400 rounded-full mr-2" title="Primary Key"></span>
                    <span className="font-bold">id_pelanggan</span>
                    <span className="ml-auto text-sm text-gray-600">INT(11)</span>
                  </div>
                  <div className="flex items-center">
                    <span className="w-4 h-4 bg-gray-300 rounded-full mr-2"></span>
                    <span>nama</span>
                    <span className="ml-auto text-sm text-gray-600">VARCHAR(100)</span>
                  </div>
                  <div className="flex items-center">
                    <span className="w-4 h-4 bg-gray-300 rounded-full mr-2"></span>
                    <span>email</span>
                    <span className="ml-auto text-sm text-gray-600">VARCHAR(100)</span>
                  </div>
                  <div className="flex items-center">
                    <span className="w-4 h-4 bg-gray-300 rounded-full mr-2"></span>
                    <span>no_telepon</span>
                    <span className="ml-auto text-sm text-gray-600">VARCHAR(15)</span>
                  </div>
                </div>
              </CardContent>
            </Card>

            {/* Entity Lapangan */}
            <Card className="border-2 border-green-500 shadow-lg">
              <CardHeader className="bg-green-500 text-white">
                <CardTitle className="text-center">LAPANGAN</CardTitle>
              </CardHeader>
              <CardContent className="p-4">
                <div className="space-y-2">
                  <div className="flex items-center">
                    <span className="w-4 h-4 bg-yellow-400 rounded-full mr-2" title="Primary Key"></span>
                    <span className="font-bold">id_lapangan</span>
                    <span className="ml-auto text-sm text-gray-600">INT(11)</span>
                  </div>
                  <div className="flex items-center">
                    <span className="w-4 h-4 bg-gray-300 rounded-full mr-2"></span>
                    <span>nama_lapangan</span>
                    <span className="ml-auto text-sm text-gray-600">VARCHAR(100)</span>
                  </div>
                  <div className="flex items-center">
                    <span className="w-4 h-4 bg-gray-300 rounded-full mr-2"></span>
                    <span>lokasi</span>
                    <span className="ml-auto text-sm text-gray-600">VARCHAR(255)</span>
                  </div>
                  <div className="flex items-center">
                    <span className="w-4 h-4 bg-gray-300 rounded-full mr-2"></span>
                    <span>harga_per_jam</span>
                    <span className="ml-auto text-sm text-gray-600">DECIMAL(10,2)</span>
                  </div>
                </div>
              </CardContent>
            </Card>

            {/* Empty space for better layout */}
            <div></div>

            {/* Entity Booking (centered) */}
            <Card className="border-2 border-purple-500 shadow-lg md:col-start-2">
              <CardHeader className="bg-purple-500 text-white">
                <CardTitle className="text-center">BOOKING</CardTitle>
              </CardHeader>
              <CardContent className="p-4">
                <div className="space-y-2">
                  <div className="flex items-center">
                    <span className="w-4 h-4 bg-yellow-400 rounded-full mr-2" title="Primary Key"></span>
                    <span className="font-bold">id_booking</span>
                    <span className="ml-auto text-sm text-gray-600">INT(11)</span>
                  </div>
                  <div className="flex items-center">
                    <span className="w-4 h-4 bg-red-400 rounded-full mr-2" title="Foreign Key"></span>
                    <span className="font-semibold text-blue-600">id_pelanggan</span>
                    <span className="ml-auto text-sm text-gray-600">INT(11)</span>
                  </div>
                  <div className="flex items-center">
                    <span className="w-4 h-4 bg-red-400 rounded-full mr-2" title="Foreign Key"></span>
                    <span className="font-semibold text-green-600">id_lapangan</span>
                    <span className="ml-auto text-sm text-gray-600">INT(11)</span>
                  </div>
                  <div className="flex items-center">
                    <span className="w-4 h-4 bg-gray-300 rounded-full mr-2"></span>
                    <span>tanggal_booking</span>
                    <span className="ml-auto text-sm text-gray-600">DATE</span>
                  </div>
                  <div className="flex items-center">
                    <span className="w-4 h-4 bg-gray-300 rounded-full mr-2"></span>
                    <span>jam_mulai</span>
                    <span className="ml-auto text-sm text-gray-600">TIME</span>
                  </div>
                  <div className="flex items-center">
                    <span className="w-4 h-4 bg-gray-300 rounded-full mr-2"></span>
                    <span>jam_selesai</span>
                    <span className="ml-auto text-sm text-gray-600">TIME</span>
                  </div>
                  <div className="flex items-center">
                    <span className="w-4 h-4 bg-gray-300 rounded-full mr-2"></span>
                    <span>status</span>
                    <span className="ml-auto text-sm text-gray-600">ENUM</span>
                  </div>
                  <div className="flex items-center">
                    <span className="w-4 h-4 bg-gray-300 rounded-full mr-2"></span>
                    <span>created_at</span>
                    <span className="ml-auto text-sm text-gray-600">TIMESTAMP</span>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>

          {/* Legend */}
          <Card className="mt-8 border-gray-300">
            <CardHeader>
              <CardTitle className="text-lg">Keterangan (Legend)</CardTitle>
            </CardHeader>
            <CardContent>
              <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div className="flex items-center">
                  <span className="w-4 h-4 bg-yellow-400 rounded-full mr-2"></span>
                  <span className="text-sm">Primary Key</span>
                </div>
                <div className="flex items-center">
                  <span className="w-4 h-4 bg-red-400 rounded-full mr-2"></span>
                  <span className="text-sm">Foreign Key</span>
                </div>
                <div className="flex items-center">
                  <span className="w-4 h-4 bg-gray-300 rounded-full mr-2"></span>
                  <span className="text-sm">Atribut Biasa</span>
                </div>
                <div className="flex items-center">
                  <span className="text-sm font-bold">1:M</span>
                  <span className="text-sm ml-2">One to Many</span>
                </div>
              </div>
            </CardContent>
          </Card>

          {/* Relationship Descriptions */}
          <Card className="mt-4 border-gray-300">
            <CardHeader>
              <CardTitle className="text-lg">Deskripsi Relasi</CardTitle>
            </CardHeader>
            <CardContent>
              <div className="space-y-3">
                <div className="p-3 bg-blue-50 rounded-lg">
                  <h4 className="font-semibold text-blue-800">PELANGGAN → BOOKING (1:M)</h4>
                  <p className="text-sm text-blue-700">
                    Satu pelanggan dapat memiliki banyak booking, tetapi setiap booking hanya dimiliki oleh satu pelanggan.
                  </p>
                </div>
                <div className="p-3 bg-green-50 rounded-lg">
                  <h4 className="font-semibold text-green-800">LAPANGAN → BOOKING (1:M)</h4>
                  <p className="text-sm text-green-700">
                    Satu lapangan dapat dibooking berkali-kali, tetapi setiap booking hanya untuk satu lapangan.
                  </p>
                </div>
              </div>
            </CardContent>
          </Card>

          {/* Business Rules */}
          <Card className="mt-4 border-gray-300">
            <CardHeader>
              <CardTitle className="text-lg">Aturan Bisnis (Business Rules)</CardTitle>
            </CardHeader>
            <CardContent>
              <div className="space-y-2 text-sm">
                <div className="flex items-start">
                  <span className="text-purple-600 mr-2">•</span>
                  <span>Setiap pelanggan harus memiliki nama dan email yang unik</span>
                </div>
                <div className="flex items-start">
                  <span className="text-purple-600 mr-2">•</span>
                  <span>Booking harus memiliki tanggal, jam mulai, dan jam selesai</span>
                </div>
                <div className="flex items-start">
                  <span className="text-purple-600 mr-2">•</span>
                  <span>Status booking dapat berupa: pending, confirmed, atau cancelled</span>
                </div>
                <div className="flex items-start">
                  <span className="text-purple-600 mr-2">•</span>
                  <span>Setiap lapangan memiliki harga per jam yang dapat berbeda</span>
                </div>
                <div className="flex items-start">
                  <span className="text-purple-600 mr-2">•</span>
                  <span>Booking otomatis tercatat waktu pembuatannya (created_at)</span>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>
      </div>
    </div>
  );
};

export default ERDDiagram;
