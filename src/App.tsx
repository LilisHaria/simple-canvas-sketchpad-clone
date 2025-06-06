
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom'
import { SidebarProvider } from '@/components/ui/sidebar'
import { AppSidebar } from '@/components/AppSidebar'
import Index from '@/pages/Index'
import BookingIndoor from '@/pages/BookingIndoor'
import BookingOutdoor from '@/pages/BookingOutdoor'
import BookingSynthetic from '@/pages/BookingSynthetic'
import History from '@/pages/History'
import NotFound from '@/pages/NotFound'
import ERD from '@/pages/ERD'
import AdminDashboard from '@/pages/AdminDashboard'
import AdminArenas from '@/pages/AdminArenas'
import AdminBookings from '@/pages/AdminBookings'
import AdminUsers from '@/pages/AdminUsers'
import AdminReports from '@/pages/AdminReports'
import './App.css'

function App() {
  return (
    <Router>
      <SidebarProvider>
        <div className="min-h-screen flex w-full">
          <AppSidebar />
          <main className="flex-1">
            <Routes>
              <Route path="/" element={<Index />} />
              <Route path="/booking" element={<Index />} />
              <Route path="/booking/indoor" element={<BookingIndoor />} />
              <Route path="/booking/outdoor" element={<BookingOutdoor />} />
              <Route path="/booking/synthetic" element={<BookingSynthetic />} />
              <Route path="/history" element={<History />} />
              <Route path="/search" element={<Index />} />
              <Route path="/erd" element={<ERD />} />
              
              {/* Admin Routes */}
              <Route path="/admin/dashboard" element={<AdminDashboard />} />
              <Route path="/admin/arenas" element={<AdminArenas />} />
              <Route path="/admin/bookings" element={<AdminBookings />} />
              <Route path="/admin/users" element={<AdminUsers />} />
              <Route path="/admin/reports" element={<AdminReports />} />
              
              <Route path="*" element={<NotFound />} />
            </Routes>
          </main>
        </div>
      </SidebarProvider>
    </Router>
  )
}

export default App
