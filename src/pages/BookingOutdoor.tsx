import React, { useState } from 'react';
import { Calendar, Clock, MapPin, Trees } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';

const BookingOutdoor = () => {
  const [selectedDate, setSelectedDate] = useState<Date | undefined>(undefined);
  const [selectedTime, setSelectedTime] = useState('');

  const handleDateSelect = (date: Date | undefined) => {
    setSelectedDate(date);
  };

  const handleTimeSelect = (time: string) => {
    setSelectedTime(time);
  };

  return (
    <div className="container mx-auto p-4">
      <Card className="w-full max-w-md mx-auto">
        <CardHeader>
          <CardTitle>Booking Lapangan Outdoor</CardTitle>
          <CardDescription>Pilih tanggal dan waktu yang tersedia.</CardDescription>
        </CardHeader>
        <CardContent className="grid gap-4">
          <div className="grid gap-2">
            <label htmlFor="date">Tanggal</label>
            <input
              type="date"
              id="date"
              className="border rounded-md px-2 py-1"
              onChange={(e) => handleDateSelect(new Date(e.target.value))}
            />
          </div>
          <div className="grid gap-2">
            <label htmlFor="time">Waktu</label>
            <select
              id="time"
              className="border rounded-md px-2 py-1"
              onChange={(e) => handleTimeSelect(e.target.value)}
            >
              <option value="">Pilih Waktu</option>
              <option value="08:00 - 10:00">08:00 - 10:00</option>
              <option value="10:00 - 12:00">10:00 - 12:00</option>
              <option value="14:00 - 16:00">14:00 - 16:00</option>
              <option value="16:00 - 18:00">16:00 - 18:00</option>
            </select>
          </div>
          <Button>Booking Sekarang</Button>
        </CardContent>
      </Card>

      <div className="mt-6">
        <Card>
          <CardHeader>
            <CardTitle>Detail Lapangan</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="flex items-center space-x-2">
              <Trees className="h-4 w-4" />
              <span>Jenis Lapangan: Outdoor</span>
            </div>
            <div className="flex items-center space-x-2 mt-2">
              <MapPin className="h-4 w-4" />
              <span>Lokasi: Area Terbuka</span>
            </div>
            <div className="flex items-center space-x-2 mt-2">
              <Clock className="h-4 w-4" />
              <span>Durasi: 2 Jam</span>
            </div>
            <div className="flex items-center space-x-2 mt-2">
              <Calendar className="h-4 w-4" />
              <span>
                Tanggal:{' '}
                {selectedDate
                  ? selectedDate.toLocaleDateString()
                  : 'Belum dipilih'}
              </span>
            </div>
            <div className="flex items-center space-x-2 mt-2">
              <Clock className="h-4 w-4" />
              <span>Waktu: {selectedTime || 'Belum dipilih'}</span>
            </div>
            <Badge className="mt-4">Harga: Rp 100.000</Badge>
          </CardContent>
        </Card>
      </div>
    </div>
  );
};

export default BookingOutdoor;
