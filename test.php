<?php
// คำสั่ง PHP เบื้องต้น (สามารถดึงข้อมูลจาก Database หรือทำ API Handler เพิ่มเติมในส่วนนี้ได้ในอนาคต)
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VOICESRI - ระบบรับฟังเพื่อพัฒนา</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"></script>
    
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
    
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Sarabun', sans-serif; }
    </style>
</head>
<body>

    <div id="root"></div>

    <script type="text/babel">
        const { useState, useEffect } = React;

        // --- MOCK DATA ---
        const MOCK_TICKETS = [
          { id: 'VOC-2026-0001', type: 'ปัญหาการปฏิบัติงาน', status: 'อยู่ระหว่างดำเนินการ', urgency: 'สูง', date: '2026-06-25', dept: 'ER' },
          { id: 'VOC-2026-0002', type: 'ชื่นชมบุคลากร', status: 'ปิดเรื่อง', urgency: 'ต่ำ', date: '2026-06-24', dept: 'OPD' },
          { id: 'VOC-2026-0003', type: 'ข้อเสนอแนะเพื่อพัฒนา', status: 'รับเรื่องแล้ว', urgency: 'ปานกลาง', date: '2026-06-26', dept: 'IPD' },
          { id: 'VOC-2026-0004', type: 'ความปลอดภัย', status: 'พิจารณา', urgency: 'วิกฤต', date: '2026-06-27', dept: 'ห้องผ่าตัด' },
        ];

        // --- LUCIDE ICON HELPER ---
        const Icon = ({ name, size = 24, className = "" }) => {
            useEffect(() => {
                if (window.lucide) {
                    window.lucide.createIcons();
                }
            }, [name]);
            return <i data-lucide={name} className={className} style={{ width: size, height: size, display: 'inline-block' }}></i>;
        };

        const Megaphone = (p) => <Icon name="megaphone" {...p} />;
        const Lightbulb = (p) => <Icon name="lightbulb" {...p} />;
        const Heart = (p) => <Icon name="heart" {...p} />;
        const Search = (p) => <Icon name="search" {...p} />;
        const CheckCircle = (p) => <Icon name="check-circle" {...p} />;
        const Clock = (p) => <Icon name="clock" {...p} />;
        const AlertTriangle = (p) => <Icon name="alert-triangle" {...p} />;
        const Inbox = (p) => <Icon name="inbox" {...p} />;
        const LayoutDashboard = (p) => <Icon name="layout-dashboard" {...p} />;
        const FileText = (p) => <Icon name="file-text" {...p} />;
        const Settings = (p) => <Icon name="settings" {...p} />;
        const LogOut = (p) => <Icon name="log-out" {...p} />;
        const Menu = (p) => <Icon name="menu" {...p} />;
        const User = (p) => <Icon name="user" {...p} />;
        const ImageIcon = (p) => <Icon name="image" {...p} />;
        const ChevronRight = (p) => <Icon name="chevron-right" {...p} />;
        const X = (p) => <Icon name="x" {...p} />;
        const ArrowLeft = (p) => <Icon name="arrow-left" {...p} />;
        const RefreshCcw = (p) => <Icon name="refresh-ccw" {...p} />;
        const TrendingUp = (p) => <Icon name="trending-up" {...p} />;
        const ShieldCheck = (p) => <Icon name="shield-check" {...p} />;

        function App() {
          const [currentView, setCurrentView] = useState('landing');
          const [selectedType, setSelectedType] = useState('');
          const [ticketId, setTicketId] = useState('');
          const [isSidebarOpen, setIsSidebarOpen] = useState(false);

          useEffect(() => {
              if (window.lucide) window.lucide.createIcons();
          });

          const goHome = () => setCurrentView('landing');
          const goToForm = (type) => { setSelectedType(type); setCurrentView('form'); };
          const goToSuccess = (id) => { setTicketId(id); setCurrentView('success'); };
          const goToTrack = (id) => { setTicketId(id); setCurrentView('track'); };
          const goToAdmin = () => setCurrentView('admin-login');
          const loginAdmin = () => setCurrentView('admin-dash');

          const handleAdminNav = (view) => {
            setCurrentView(view);
            setIsSidebarOpen(false); 
          };

          return (
            <div className="min-h-screen bg-emerald-50/30 font-sans text-slate-800">
              {!currentView.startsWith('admin') && (
                <div className="md:py-10 min-h-screen flex flex-col items-center">
                  <div className="w-full max-w-md mx-auto bg-white min-h-screen md:min-h-[85vh] shadow-2xl relative overflow-hidden flex flex-col md:rounded-3xl border border-emerald-100">
                    
                    <div className="bg-emerald-700 text-white p-6 rounded-b-3xl shadow-md relative overflow-hidden">
                      <div className="absolute -right-10 -top-10 w-32 h-32 bg-emerald-600 rounded-full opacity-50 blur-2xl"></div>
                      
                      <div className="flex justify-between items-center mb-2 relative z-10">
                        <h1 className="text-2xl font-bold tracking-tight flex items-center gap-2" onClick={goHome} style={{cursor:'pointer'}}>
                          VOICESRI
                        </h1>
                        {currentView !== 'landing' && <button onClick={goHome} className="bg-emerald-800/50 p-2 rounded-full hover:bg-emerald-800"><X size={20} /></button>}
                      </div>
                      {currentView === 'landing' && <p className="text-emerald-100 text-sm relative z-10">รับฟัง ตอบกลับ ติดตาม เพื่อพัฒนาไปด้วยกัน</p>}
                    </div>

                    <div className="flex-1 overflow-y-auto pb-20 w-full">
                      {currentView === 'landing' && <LandingView goToForm={goToForm} goToTrack={goToTrack} goToAdmin={goToAdmin} />}
                      {currentView === 'form' && <FormView type={selectedType} onSubmit={() => goToSuccess('VOC-2026-0005')} onCancel={goHome} />}
                      {currentView === 'success' && <SuccessView id={ticketId} goHome={goHome} goToTrack={() => goToTrack(ticketId)} />}
                      {currentView === 'track' && <TrackView id={ticketId} goHome={goHome} />}
                    </div>
                  </div>
                </div>
              )}

              {currentView.startsWith('admin') && (
                <div className="min-h-screen flex bg-slate-50">
                  {currentView === 'admin-login' ? (
                    <AdminLogin onLogin={loginAdmin} goHome={goHome} />
                  ) : (
                    <React.Fragment>
                      {isSidebarOpen && (
                        <div 
                          className="fixed inset-0 bg-slate-900/40 z-20 lg:hidden backdrop-blur-sm transition-opacity"
                          onClick={() => setIsSidebarOpen(false)}
                        />
                      )}
                      
                      <Sidebar currentView={currentView} setCurrentView={handleAdminNav} onLogout={goHome} isSidebarOpen={isSidebarOpen} />
                      
                      <div className="flex-1 flex flex-col overflow-hidden w-full relative">
                        <AdminHeader onMenuClick={() => setIsSidebarOpen(true)} />
                        <main className="flex-1 overflow-y-auto p-4 md:p-6">
                          {currentView === 'admin-dash' && <DashboardView />}
                          {currentView === 'admin-tickets' && <TicketListView setCurrentView={handleAdminNav} />}
                          {currentView === 'admin-detail' && <TicketDetailView setCurrentView={handleAdminNav} />}
                          {currentView === 'admin-reports' && <ReportsView />}
                          {currentView === 'admin-settings' && <SettingsView />}
                        </main>
                      </div>
                    </React.Fragment>
                  )}
                </div>
              )}
            </div>
          );
        }

        /* ================= FRONTEND COMPONENTS ================= */

        function LandingView({ goToForm, goToTrack, goToAdmin }) {
          const [searchId, setSearchId] = useState('');

          return (
            <div className="p-6 space-y-6">
              <div className="bg-gradient-to-r from-emerald-50 to-orange-50 border border-emerald-100 rounded-2xl p-5 flex items-center relative overflow-hidden shadow-sm">
                <div className="w-2/3 z-10 relative">
                  <div className="inline-block bg-orange-100 text-orange-600 text-[10px] font-bold px-2 py-1 rounded-full mb-2">มาสคอตประจำระบบ</div>
                  <h2 className="text-lg font-bold text-emerald-800 mb-1">สวัสดีครับ!</h2>
                  <p className="text-xs text-emerald-700 leading-relaxed font-medium">ผมชื่อ <span className="text-orange-600 font-bold">เสียงเสรี</span> ยินดีรับฟัง<br/>ทุกเสียงนะครับ</p>
                </div>
                <div className="w-1/2 absolute -right-4 -bottom-6">
                   <img src="https://placehold.co/400x400/2f7c47/fff?text=Mascot+Image" alt="มาสคอตเสียงเสรี" className="w-full h-full object-cover opacity-90 drop-shadow-lg" />
                </div>
              </div>

              <div className="space-y-4">
                <h2 className="text-md font-bold text-slate-700">คุณต้องการทำอะไรวันนี้?</h2>
                
                <button onClick={() => goToForm('ปัญหาการปฏิบัติงาน')} className="w-full bg-white border border-slate-200 hover:border-red-400 hover:shadow-md transition-all p-4 rounded-2xl flex items-center gap-4 group">
                  <div className="bg-red-50 text-red-500 p-3 rounded-xl group-hover:bg-red-500 group-hover:text-white transition-colors"><AlertTriangle size={24} /></div>
                  <div className="text-left flex-1">
                    <h3 className="font-semibold text-slate-800">แจ้งปัญหา</h3>
                    <p className="text-xs text-slate-500">รายงานปัญหา อุปสรรค หรือความเสี่ยง</p>
                  </div>
                  <ChevronRight className="text-slate-300 group-hover:text-red-400" />
                </button>

                <button onClick={() => goToForm('ข้อเสนอแนะเพื่อพัฒนา')} className="w-full bg-white border border-slate-200 hover:border-orange-400 hover:shadow-md transition-all p-4 rounded-2xl flex items-center gap-4 group">
                  <div className="bg-orange-50 text-orange-500 p-3 rounded-xl group-hover:bg-orange-500 group-hover:text-white transition-colors"><Lightbulb size={24} /></div>
                  <div className="text-left flex-1">
                    <h3 className="font-semibold text-slate-800">เสนอแนวทางพัฒนา</h3>
                    <p className="text-xs text-slate-500">ไอเดียใหม่ๆ เพื่อปรับปรุงองค์กร</p>
                  </div>
                  <ChevronRight className="text-slate-300 group-hover:text-orange-400" />
                </button>

                <button onClick={() => goToForm('ชื่นชมบุคลากร/หน่วยงาน')} className="w-full bg-white border border-slate-200 hover:border-pink-400 hover:shadow-md transition-all p-4 rounded-2xl flex items-center gap-4 group">
                  <div className="bg-pink-50 text-pink-500 p-3 rounded-xl group-hover:bg-pink-500 group-hover:text-white transition-colors"><Heart size={24} /></div>
                  <div className="text-left flex-1">
                    <h3 className="font-semibold text-slate-800">ชื่นชมบุคลากร</h3>
                    <p className="text-xs text-slate-500">ส่งต่อกำลังใจให้คนทำงาน</p>
                  </div>
                  <ChevronRight className="text-slate-300 group-hover:text-pink-400" />
                </button>
              </div>

              <div className="bg-emerald-50/80 border border-emerald-100 p-5 rounded-2xl mt-8 shadow-sm">
                <h2 className="text-sm font-semibold text-emerald-800 mb-3 flex items-center gap-2">
                  <Search size={16} className="text-emerald-600" /> ติดตามสถานะเรื่องของคุณ
                </h2>
                <div className="flex gap-2">
                  <input 
                    type="text" 
                    placeholder="เช่น VOC-2026-0001" 
                    className="flex-1 p-3 rounded-xl border border-emerald-200 text-sm focus:outline-none focus:border-emerald-500 bg-white"
                    value={searchId}
                    onChange={(e) => setSearchId(e.target.value)}
                  />
                  <button 
                    onClick={() => searchId && goToTrack(searchId)}
                    className="bg-emerald-600 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-emerald-700 shadow-md shadow-emerald-200"
                  >
                    ค้นหา
                  </button>
                </div>
              </div>

              <div className="text-center pt-8">
                <button onClick={goToAdmin} className="text-xs text-slate-400 hover:text-emerald-600 underline">เข้าสู่ระบบสำหรับเจ้าหน้าที่ (Admin)</button>
              </div>
            </div>
          );
        }

        function FormView({ type, onSubmit, onCancel }) {
          const isProblem = type.includes('ปัญหา') || type.includes('ความปลอดภัย') || type.includes('สิ่งแวดล้อม');
          const [wantsContact, setWantsContact] = useState(false);
          
          return (
            <div className="animate-in fade-in slide-in-from-bottom-4 duration-300">
              <div className="bg-white p-6 space-y-6">
                
                <div className="space-y-4">
                  <div className="flex items-center justify-between border-b border-emerald-100 pb-2">
                    <h3 className="font-bold text-emerald-800 flex items-center gap-2">
                      <span className="bg-emerald-100 text-emerald-700 w-6 h-6 rounded-full flex items-center justify-center text-xs">1</span> 
                      ข้อมูลเรื่องราว
                    </h3>
                  </div>
                  
                  <div>
                    <label className="block text-sm font-medium text-slate-700 mb-1">ประเภทเรื่อง <span className="text-red-500">*</span></label>
                    <select className="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:border-emerald-500 outline-none" defaultValue={type}>
                      <option>ปัญหาการปฏิบัติงาน</option>
                      <option>ข้อเสนอแนะเพื่อพัฒนา</option>
                      <option>ชื่นชมบุคลากร/หน่วยงาน</option>
                      <option>ความปลอดภัย</option>
                      <option>สิ่งแวดล้อม</option>
                      <option>เทคโนโลยีสารสนเทศ</option>
                      <option>ทรัพยากรบุคคล</option>
                      <option>การเรียนการสอน</option>
                      <option>งานวิจัย</option>
                      <option>อื่น ๆ (โปรดระบุในรายละเอียด)</option>
                    </select>
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-slate-700 mb-1">รายละเอียด <span className="text-red-500">*</span></label>
                    <textarea rows="3" placeholder="พิมพ์ข้อความที่ต้องการสื่อสาร..." className="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:border-emerald-500 outline-none resize-none"></textarea>
                  </div>

                  {isProblem && (
                    <div className="bg-orange-50/50 p-4 rounded-xl border border-orange-100">
                      <label className="block text-sm font-medium text-slate-700 mb-2">ผลกระทบที่เกิดขึ้น (เลือกได้หลายข้อ)</label>
                      <div className="grid grid-cols-2 gap-2">
                        {['กระทบผู้ป่วย', 'กระทบนักศึกษา', 'กระทบบุคลากร', 'กระทบคุณภาพบริการ', 'กระทบความปลอดภัย', 'กระทบภาพลักษณ์องค์กร'].map(item => (
                          <label key={item} className="flex items-center gap-2 p-2 border rounded-lg bg-white border-orange-200 text-sm cursor-pointer hover:bg-orange-100/50">
                            <input type="checkbox" className="rounded text-orange-500 focus:ring-orange-500" />
                            <span className="text-slate-600 text-xs">{item}</span>
                          </label>
                        ))}
                        <label className="col-span-2 flex items-center gap-2 p-2 border rounded-lg bg-white border-orange-200 text-sm cursor-pointer hover:bg-orange-100/50">
                          <input type="checkbox" className="rounded text-orange-500 focus:ring-orange-500" />
                          <span className="text-slate-600 text-xs font-medium">ยังไม่เกิดผลกระทบ แต่มีความเสี่ยง</span>
                        </label>
                      </div>
                    </div>
                  )}
                </div>

                <div className="space-y-4 pt-4">
                  <div className="flex items-center justify-between border-b border-emerald-100 pb-2">
                    <h3 className="font-bold text-emerald-800 flex items-center gap-2">
                      <span className="bg-emerald-100 text-emerald-700 w-6 h-6 rounded-full flex items-center justify-center text-xs">2</span> 
                      บริบทและหลักฐาน
                    </h3>
                  </div>
                  
                  <div className="grid grid-cols-2 gap-4">
                     <div>
                      <label className="block text-sm font-medium text-slate-700 mb-1">สถานที่/หน่วยงาน <span className="text-red-500">*</span></label>
                      <select className="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-emerald-500">
                        <option value="">เลือกสถานที่</option>
                        <option>OPD</option><option>IPD</option><option>ER</option><option>ห้องผ่าตัด</option><option>ศูนย์หัวใจ</option><option>ห้องเรียน</option><option>หน่วยงานสนับสนุน</option>
                      </select>
                    </div>
                    <div>
                      <label className="block text-sm font-medium text-slate-700 mb-1">วันที่เกิดเหตุการณ์</label>
                      <input type="date" className="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none focus:border-emerald-500" />
                    </div>
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-slate-700 mb-1">แนบรูปภาพ/เอกสาร (ไม่บังคับ)</label>
                    <div className="border-2 border-dashed border-emerald-200 bg-emerald-50/30 rounded-xl p-6 text-center hover:bg-emerald-50 cursor-pointer transition-colors">
                      <ImageIcon className="mx-auto text-emerald-400 mb-2" size={32} />
                      <p className="text-sm text-emerald-600 font-medium">แตะเพื่อถ่ายรูป หรือเลือกจากคลัง</p>
                    </div>
                  </div>
                </div>

                <div className="space-y-4 pt-4">
                  <div className="flex items-center justify-between border-b border-emerald-100 pb-2">
                    <h3 className="font-bold text-emerald-800 flex items-center gap-2">
                      <span className="bg-emerald-100 text-emerald-700 w-6 h-6 rounded-full flex items-center justify-center text-xs">3</span> 
                      การติดตามผล
                    </h3>
                  </div>
                  
                  <div className="space-y-4">
                     <div>
                       <label className="block text-sm font-medium text-slate-700 mb-2">ต้องการเปิดเผยตัวตนหรือไม่?</label>
                       <div className="flex gap-4">
                         <label className="flex items-center gap-2 cursor-pointer">
                           <input type="radio" name="identity" className="text-emerald-600 focus:ring-emerald-500 w-4 h-4" defaultChecked />
                           <span className="text-sm text-slate-700">ไม่เปิดเผย (Anonymous)</span>
                         </label>
                         <label className="flex items-center gap-2 cursor-pointer">
                           <input type="radio" name="identity" className="text-emerald-600 focus:ring-emerald-500 w-4 h-4" />
                           <span className="text-sm text-slate-700">เปิดเผยตัวตน</span>
                         </label>
                       </div>
                     </div>

                     <div>
                       <label className="block text-sm font-medium text-slate-700 mb-2">ต้องการให้ติดต่อกลับหรือไม่?</label>
                       <div className="flex gap-4 mb-3">
                         <label className="flex items-center gap-2 cursor-pointer" onClick={() => setWantsContact(false)}>
                           <input type="radio" name="contact" className="text-emerald-600 focus:ring-emerald-500 w-4 h-4" defaultChecked />
                           <span className="text-sm text-slate-700">ไม่จำเป็น</span>
                         </label>
                         <label className="flex items-center gap-2 cursor-pointer" onClick={() => setWantsContact(true)}>
                           <input type="radio" name="contact" className="text-emerald-600 focus:ring-emerald-500 w-4 h-4" />
                           <span className="text-sm text-slate-700">ต้องการ</span>
                         </label>
                       </div>
                       
                       {wantsContact && (
                         <div className="animate-in fade-in slide-in-from-top-2">
                           <input type="text" placeholder="ระบุช่องทางติดต่อ (เบอร์โทรศัพท์, อีเมล, LINE)" className="w-full p-3 bg-slate-50 border border-emerald-200 rounded-xl text-sm focus:border-emerald-500 outline-none" />
                         </div>
                       )}
                     </div>
                  </div>
                </div>

                <div className="pt-6 pb-8 flex gap-3">
                  <button onClick={onCancel} className="flex-1 py-3 px-4 bg-slate-100 text-slate-600 font-medium rounded-xl hover:bg-slate-200 transition">ยกเลิก</button>
                  <button onClick={onSubmit} className="flex-[2] py-3 px-4 bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 transition shadow-lg shadow-emerald-200">ส่งข้อมูล</button>
                </div>

              </div>
            </div>
          );
        }

        function SuccessView({ id, goHome, goToTrack }) {
          return (
            <div className="p-8 text-center animate-in zoom-in-95 duration-500 flex flex-col items-center justify-center min-h-[60vh]">
              <div className="mb-4 relative">
                <div className="absolute inset-0 bg-orange-100 rounded-full blur-xl opacity-50"></div>
                <img src="https://placehold.co/400x400/2f7c47/fff?text=Mascot+Happy" alt="เสียงเสรีขอบคุณ" className="w-32 h-32 object-cover relative z-10 mx-auto rounded-full border-4 border-white shadow-lg" />
              </div>

              <h2 className="text-2xl font-bold text-emerald-800 mb-2">ขอบคุณจากใจ!</h2>
              <p className="text-slate-600 text-sm mb-8">ที่ร่วมเป็นส่วนหนึ่งในการพัฒนาองค์กรของเรา</p>
              
              <div className="bg-emerald-50 border border-emerald-100 rounded-2xl p-6 w-full mb-8 shadow-inner">
                <p className="text-xs text-emerald-600 uppercase tracking-wider mb-1 font-bold">Ticket ID ของคุณคือ</p>
                <p className="text-2xl font-mono font-bold text-emerald-700">{id}</p>
                <p className="text-xs text-slate-500 mt-2">* กรุณาบันทึกรหัสนี้ไว้เพื่อใช้ติดตามความคืบหน้า</p>
              </div>

              <div className="space-y-3 w-full">
                <button onClick={goToTrack} className="w-full py-3 px-4 bg-orange-500 text-white font-bold rounded-xl hover:bg-orange-600 transition shadow-md shadow-orange-200">ดูสถานะตอนนี้เลย</button>
                <button onClick={goHome} className="w-full py-3 px-4 bg-white border border-slate-200 text-slate-600 font-medium rounded-xl hover:bg-slate-50 transition">กลับหน้าหลัก</button>
              </div>
            </div>
          );
        }

        function TrackView({ id, goHome }) {
          return (
            <div className="p-6">
              <button onClick={goHome} className="flex items-center gap-2 text-emerald-600 text-sm font-medium mb-6">
                <ArrowLeft size={16} /> กลับ
              </button>

              <div className="mb-6 flex justify-between items-end">
                <div>
                  <h2 className="text-xl font-bold text-emerald-900">สถานะการดำเนินการ</h2>
                  <p className="text-sm text-slate-500 font-mono mt-1">Ticket ID: {id || 'VOC-2026-0001'}</p>
                </div>
                <div className="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center overflow-hidden border-2 border-white shadow-sm">
                   <img src="https://placehold.co/100x100/2f7c47/fff?text=Mascot" alt="Mascot" className="w-full h-full object-cover" />
                </div>
              </div>

              <div className="relative pl-6 border-l-2 border-emerald-100 space-y-8 mb-8">
                <div className="relative">
                  <div className="absolute -left-[35px] bg-emerald-600 w-6 h-6 rounded-full border-4 border-white flex items-center justify-center">
                    <Check size={12} className="text-white" />
                  </div>
                  <p className="text-sm font-semibold text-slate-800">รับเรื่องแล้ว</p>
                  <p className="text-xs text-slate-500">27 มิ.ย. 2026, 09:30 น.</p>
                </div>

                <div className="relative">
                  <div className="absolute -left-[35px] bg-emerald-600 w-6 h-6 rounded-full border-4 border-white flex items-center justify-center">
                    <Check size={12} className="text-white" />
                  </div>
                  <p className="text-sm font-semibold text-slate-800">อยู่ระหว่างพิจารณา</p>
                  <p className="text-xs text-slate-500">27 มิ.ย. 2026, 10:15 น.</p>
                </div>

                <div className="relative">
                  <div className="absolute -left-[35px] bg-orange-400 w-6 h-6 rounded-full border-4 border-white animate-pulse shadow-md shadow-orange-200"></div>
                  <p className="text-sm font-semibold text-orange-600">อยู่ระหว่างดำเนินการ</p>
                  <p className="text-xs text-slate-500">คาดว่าจะแล้วเสร็จภายใน 3 วัน</p>
                  
                  <div className="mt-3 bg-emerald-50 border border-emerald-200 rounded-xl p-4 relative shadow-sm">
                     <div className="absolute -top-2 left-4 w-4 h-4 bg-emerald-50 rotate-45 border-l border-t border-emerald-200"></div>
                     <p className="text-xs font-bold text-emerald-800 mb-1 flex items-center gap-1">
                       <ShieldCheck size={14} className="text-emerald-600"/> ข้อความตอบกลับจากหน่วยงาน:
                     </p>
                     <p className="text-sm text-slate-700 leading-relaxed">
                       "รับทราบปัญหาเรื่องแอร์ห้องพักญาติไม่เย็นครับ ตอนนี้ได้แจ้งช่างซ่อมบำรุงเข้าตรวจสอบแล้ว เบื้องต้นพบน้ำยาแอร์ขาด กำลังเติมน้ำยาครับ"
                     </p>
                  </div>
                </div>

                <div className="relative opacity-40">
                  <div className="absolute -left-[35px] bg-slate-300 w-6 h-6 rounded-full border-4 border-white"></div>
                  <p className="text-sm font-semibold text-slate-500">ดำเนินการแล้ว / ปิดเรื่อง</p>
                </div>
              </div>
            </div>
          );
        }

        function Check(props) {
          return (
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="3" strokeLinecap="round" strokeLinejoin="round" {...props}>
              <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
          );
        }

        /* ================= BACKEND COMPONENTS ================= */

        function AdminLogin({ onLogin, goHome }) {
          return (
            <div className="w-full flex items-center justify-center min-h-screen bg-slate-100">
              <div className="bg-white p-8 rounded-2xl shadow-xl border border-slate-100 w-full max-w-md text-center">
                <div className="mb-6 relative inline-block">
                  <div className="absolute inset-0 bg-emerald-100 rounded-full blur-xl opacity-60"></div>
                  <img src="https://placehold.co/200x200/2f7c47/fff?text=Mascot" alt="เสียงเสรี" className="w-24 h-24 object-cover relative z-10 mx-auto rounded-full border-4 border-white shadow-sm" />
                </div>

                <h1 className="text-2xl font-bold text-emerald-900 mb-1">VOICESRI Admin</h1>
                <p className="text-sm text-slate-500 mb-8">ระบบจัดการเสียงเพื่อการพัฒนา</p>

                <div className="space-y-4 text-left">
                  <div>
                    <label className="text-sm font-medium text-slate-600">Email / Username</label>
                    <input type="text" defaultValue="admin" readOnly className="w-full mt-1 p-3 bg-slate-50 border border-slate-200 rounded-xl outline-none" />
                  </div>
                  <div>
                    <label className="text-sm font-medium text-slate-600">Password</label>
                    <input type="password" defaultValue="password" readOnly className="w-full mt-1 p-3 bg-slate-50 border border-slate-200 rounded-xl outline-none" />
                  </div>
                  <button onClick={onLogin} className="w-full bg-emerald-600 text-white font-bold p-3 rounded-xl hover:bg-emerald-700 mt-4 transition shadow-md shadow-emerald-200">
                    เข้าสู่ระบบ
                  </button>
                  <div className="text-center pt-4">
                     <button onClick={goHome} className="text-sm text-slate-400 hover:text-emerald-600">กลับหน้าแรกของผู้ใช้ทั่วไป</button>
                  </div>
                </div>
              </div>
            </div>
          );
        }

        function Sidebar({ currentView, setCurrentView, onLogout, isSidebarOpen }) {
          const menu = [
            { id: 'admin-dash', label: 'Dashboard', icon: LayoutDashboard },
            { id: 'admin-tickets', label: 'Tickets Inbox', icon: Inbox },
            { id: 'admin-reports', label: 'Reports', icon: FileText },
            { id: 'admin-settings', label: 'Settings', icon: Settings },
          ];

          return (
            <div className={`fixed lg:static inset-y-0 left-0 z-30 w-64 bg-white border-r border-slate-200 flex flex-col transform transition-transform duration-300 ease-in-out ${isSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'}`}>
              <div className="h-20 flex items-center px-6 gap-3 border-b border-slate-100 bg-emerald-50/50">
                 <img src="https://placehold.co/100x100/2f7c47/fff?text=Mascot" alt="Logo" className="w-8 h-8 object-cover rounded-full border border-emerald-200 shadow-sm" />
                 <span className="text-xl font-bold text-slate-800 tracking-tight">VOICE<span className="text-emerald-600">SRI</span></span>
              </div>
              <nav className="flex-1 px-4 py-6 space-y-2">
                {menu.map(item => (
                  <button 
                    key={item.id}
                    onClick={() => setCurrentView(item.id)}
                    className={`w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-colors ${
                      currentView.includes(item.id.split('-')[1]) 
                        ? 'bg-emerald-600 text-white shadow-md shadow-emerald-200' 
                        : 'text-slate-500 hover:bg-emerald-50 hover:text-emerald-700'
                    }`}
                  >
                    <item.icon size={18} /> {item.label}
                  </button>
                ))}
              </nav>
              <div className="p-4 border-t border-slate-100">
                <button onClick={onLogout} className="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-500 hover:text-red-500 transition-colors bg-slate-50 rounded-xl">
                  <LogOut size={18} /> ออกจากระบบ
                </button>
              </div>
            </div>
          );
        }

        function AdminHeader({ onMenuClick }) {
          return (
            <header className="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 px-4 md:px-6 flex items-center justify-between sticky top-0 z-10">
              <div className="flex items-center gap-3 w-full">
                <button onClick={onMenuClick} className="p-2 -ml-2 rounded-lg text-slate-500 hover:bg-slate-100 lg:hidden">
                  <Menu size={24} />
                </button>
                <div className="hidden md:flex items-center bg-slate-100 px-4 py-2 rounded-full w-96 border border-transparent focus-within:border-emerald-300 focus-within:bg-white transition-all">
                  <Search size={18} className="text-slate-400 mr-2" />
                  <input type="text" placeholder="ค้นหา Ticket ID, เรื่อง..." className="bg-transparent border-none outline-none text-sm w-full" />
                </div>
              </div>
              <div className="flex items-center gap-2 md:gap-4 shrink-0">
                <button className="relative text-slate-400 hover:text-emerald-600 p-2">
                  <Inbox size={20} />
                  <span className="absolute top-1 right-1 w-2.5 h-2.5 bg-orange-500 border-2 border-white rounded-full"></span>
                </button>
                <div className="h-8 w-px bg-slate-200 mx-2"></div>
                <div className="flex items-center gap-3 cursor-pointer">
                  <div className="text-right hidden md:block">
                    <p className="text-sm font-bold text-slate-800">Admin</p>
                    <p className="text-xs text-emerald-600 font-medium">จัดการระบบ</p>
                  </div>
                  <div className="w-10 h-10 bg-emerald-100 border border-emerald-200 rounded-full flex items-center justify-center text-emerald-600">
                    <User size={20} />
                  </div>
                </div>
              </div>
            </header>
          );
        }

        function DashboardView() {
          return (
            <div className="space-y-6 animate-in fade-in">
              <div className="flex justify-between items-end">
                <div>
                  <h1 className="text-2xl font-bold text-slate-800">Dashboard</h1>
                  <p className="text-slate-500 text-sm mt-1">ภาพรวมข้อมูลการรับฟังเสียง VOC (แยกตามไตรมาส)</p>
                </div>
                <button className="bg-white border border-slate-200 text-slate-600 px-4 py-2 rounded-lg text-sm flex items-center gap-2 hover:bg-slate-50 shadow-sm font-medium">
                  Export CSV / Excel
                </button>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div className="bg-white rounded-2xl p-6 border border-emerald-100 relative overflow-hidden shadow-sm">
                   <div className="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center mb-4"><Inbox size={20} /></div>
                   <h3 className="text-3xl font-bold text-slate-800 mb-1">1,204</h3>
                   <p className="text-sm font-medium text-slate-600">จำนวน VOC ทั้งหมด</p>
                   <p className="text-xs text-emerald-600 mt-2 font-medium">แบ่งเป็น 55% ปัญหา, 45% ชม/เสนอ</p>
                </div>
                <div className="bg-white rounded-2xl p-6 border border-orange-100 shadow-sm">
                   <div className="w-10 h-10 bg-orange-50 text-orange-500 rounded-lg flex items-center justify-center mb-4"><CheckCircle size={20} /></div>
                   <h3 className="text-3xl font-bold text-slate-800 mb-1">85%</h3>
                   <p className="text-sm font-medium text-slate-600">% เรื่องที่ปิดได้ (Resolution)</p>
                   <p className="text-xs text-orange-600 mt-2 font-medium">1,023 Closed Tickets</p>
                </div>
                <div className="bg-white rounded-2xl p-6 border border-teal-100 shadow-sm">
                   <div className="w-10 h-10 bg-teal-50 text-teal-600 rounded-lg flex items-center justify-center mb-4"><Clock size={20} /></div>
                   <h3 className="text-3xl font-bold text-slate-800 mb-1">2.4h</h3>
                   <p className="text-sm font-medium text-slate-600">Median Response Time</p>
                   <p className="text-xs text-teal-600 mt-2 font-medium">92% ตอบกลับภายใน SLA</p>
                </div>
                <div className="bg-white rounded-2xl p-6 border border-blue-100 shadow-sm">
                   <div className="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center mb-4"><TrendingUp size={20} /></div>
                   <h3 className="text-3xl font-bold text-slate-800 mb-1">45</h3>
                   <p className="text-sm font-medium text-slate-600">Improvement เกิดขึ้นจริง</p>
                   <p className="text-xs text-blue-600 mt-2 font-medium">จากข้อเสนอแนะ 210 เรื่อง</p>
                </div>
              </div>

              <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div className="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm lg:col-span-2">
                  <div className="flex justify-between items-center mb-6">
                    <h3 className="text-lg font-bold text-slate-800">จำนวน VOC แยกตามประเภท</h3>
                    <select className="text-sm border border-slate-200 rounded-md p-1 outline-none text-slate-600 bg-slate-50"><option>แยกตามประเภท</option><option>แยกตามหน่วยงาน</option></select>
                  </div>
                  <div className="h-64 flex items-end justify-around pb-4 border-b border-slate-100 relative">
                     {[40, 70, 30, 90, 50, 60, 20].map((h, i) => (
                        <div key={i} className="flex gap-1 w-12 items-end justify-center group relative">
                           <div className="w-4 bg-emerald-500 rounded-t-sm transition-all group-hover:bg-emerald-600" style={{height: `${h}%`}}></div>
                           <div className="w-4 bg-orange-400 rounded-t-sm transition-all group-hover:bg-orange-500" style={{height: `${h * 0.6}%`}}></div>
                           <span className="absolute -bottom-6 text-xs text-slate-400">Day {i+1}</span>
                        </div>
                     ))}
                     <div className="absolute w-full top-0 border-t border-slate-100"></div>
                     <div className="absolute w-full top-1/2 border-t border-slate-100 border-dashed"></div>
                  </div>
                  <div className="mt-8 flex justify-center gap-6">
                     <div className="flex items-center gap-2"><span className="w-3 h-3 rounded-full bg-emerald-500"></span><span className="text-xs text-slate-500">ปัญหา</span></div>
                     <div className="flex items-center gap-2"><span className="w-3 h-3 rounded-full bg-orange-400"></span><span className="text-xs text-slate-500">ข้อเสนอแนะ/ชื่นชม</span></div>
                  </div>
                </div>

                <div className="space-y-6">
                  <div className="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <h3 className="text-lg font-bold text-slate-800 mb-6">Top 5 Pain Points</h3>
                    <div className="space-y-4">
                      {[
                        { name: 'แอร์ไม่เย็น (OPD)', val: 45, color: 'bg-red-500' },
                        { name: 'ระบบ IT ช้า', val: 29, color: 'bg-orange-400' },
                        { name: 'ที่จอดรถไม่พอ', val: 18, color: 'bg-amber-400' },
                        { name: 'รอคิวนาน (ER)', val: 15, color: 'bg-emerald-400' },
                        { name: 'พฤติกรรมบริการ', val: 8, color: 'bg-blue-400' },
                      ].map((item, i) => (
                        <div key={i}>
                          <div className="flex justify-between text-sm mb-1">
                            <span className="font-medium text-slate-700">{item.name}</span>
                            <span className="text-slate-500">{item.val}%</span>
                          </div>
                          <div className="w-full bg-slate-100 rounded-full h-1.5">
                            <div className={`${item.color} h-1.5 rounded-full`} style={{ width: `${item.val}%` }}></div>
                          </div>
                        </div>
                      ))}
                    </div>
                  </div>

                  <div className="bg-orange-50/50 p-6 rounded-2xl border border-orange-100 shadow-sm">
                    <div className="flex items-center gap-2 mb-4">
                      <RefreshCcw size={18} className="text-orange-600" />
                      <h3 className="text-md font-bold text-orange-900">Recurring Issues (เกิดซ้ำ)</h3>
                    </div>
                    <ul className="text-sm text-slate-700 space-y-2 list-disc list-inside">
                       <li>ห้องน้ำตึกผู้ป่วยนอกน้ำไม่ไหล <span className="text-orange-600 font-semibold">(12 ครั้ง)</span></li>
                       <li>ลิฟต์ตัวที่ 3 ค้างบ่อย <span className="text-orange-600 font-semibold">(5 ครั้ง)</span></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          );
        }

        function TicketListView({ setCurrentView }) {
          return (
            <div className="space-y-6 animate-in fade-in">
               <h1 className="text-2xl font-bold text-slate-800">Ticket Inbox</h1>
               <div className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                  <div className="p-4 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-slate-50/50">
                     <div className="flex gap-2 w-full md:w-auto">
                        <select className="p-2 border border-slate-200 rounded-lg text-sm bg-white text-slate-600 outline-none flex-1 focus:border-emerald-500"><option>ทุกสถานะ</option></select>
                        <select className="p-2 border border-slate-200 rounded-lg text-sm bg-white text-slate-600 outline-none flex-1 focus:border-emerald-500"><option>ทุกความเร่งด่วน</option></select>
                     </div>
                     <div className="relative w-full md:w-auto">
                        <Search size={16} className="absolute left-3 top-3 md:top-2.5 text-slate-400" />
                        <input type="text" placeholder="ค้นหา Ticket ID..." className="pl-9 pr-4 py-2 border border-slate-200 rounded-lg text-sm outline-none w-full md:w-64 focus:border-emerald-500" />
                     </div>
                  </div>
                  
                  <div className="overflow-x-auto w-full">
                    <table className="w-full text-left border-collapse min-w-[800px]">
                      <thead>
                        <tr className="text-xs text-slate-500 uppercase tracking-wider bg-slate-50 border-b border-slate-200">
                          <th className="p-4 font-semibold">Ticket ID</th>
                          <th className="p-4 font-semibold">ประเภท</th>
                          <th className="p-4 font-semibold">หน่วยงาน</th>
                          <th className="p-4 font-semibold">วันที่รับแจ้ง</th>
                          <th className="p-4 font-semibold">ความเร่งด่วน</th>
                          <th className="p-4 font-semibold">สถานะ</th>
                          <th className="p-4 font-semibold"></th>
                      </tr>
                    </thead>
                    <tbody>
                      {MOCK_TICKETS.map((ticket, i) => (
                        <tr key={i} className="border-b border-slate-50 hover:bg-emerald-50/30 transition cursor-pointer" onClick={() => setCurrentView('admin-detail')}>
                          <td className="p-4 text-sm font-bold text-emerald-700">{ticket.id}</td>
                          <td className="p-4 text-sm text-slate-700">{ticket.type}</td>
                          <td className="p-4 text-sm text-slate-500">{ticket.dept}</td>
                          <td className="p-4 text-sm text-slate-500">{ticket.date}</td>
                          <td className="p-4">
                             <span className={`text-xs px-2.5 py-1 rounded-md font-bold ${
                               ticket.urgency === 'สูง' || ticket.urgency === 'วิกฤต' ? 'bg-red-50 text-red-600 border border-red-100' :
                               ticket.urgency === 'ปานกลาง' ? 'bg-orange-50 text-orange-600 border border-orange-100' : 'bg-slate-100 text-slate-600 border border-slate-200'
                             }`}>
                               {ticket.urgency}
                             </span>
                          </td>
                          <td className="p-4">
                             <span className={`text-xs px-3 py-1 rounded-full border font-bold ${
                               ticket.status === 'ปิดเรื่อง' ? 'bg-teal-50 border-teal-200 text-teal-700' :
                               ticket.status === 'อยู่ระหว่างดำเนินการ' ? 'bg-orange-50 border-orange-200 text-orange-600' : 
                               'bg-emerald-50 border-emerald-200 text-emerald-700'
                             }`}>
                               {ticket.status}
                             </span>
                          </td>
                          <td className="p-4 text-right">
                            <button className="text-slate-400 hover:text-emerald-600"><ChevronRight size={18} /></button>
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                  </div>
                  
                  <div className="p-4 text-center border-t border-slate-100 text-sm text-slate-500 bg-slate-50/50">
                     Showing 1 to 4 of 1,204 entries
                  </div>
               </div>
            </div>
          );
        }

        function TicketDetailView({ setCurrentView }) {
          return (
            <div className="space-y-6 animate-in slide-in-from-right-8 duration-300">
               <button onClick={() => setCurrentView('admin-tickets')} className="flex items-center gap-2 text-slate-500 hover:text-emerald-600 text-sm font-medium transition-colors">
                 <ArrowLeft size={16} /> กลับไปหน้ารายการ
               </button>
               
               <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                  <div className="lg:col-span-2 space-y-6">
                     <div className="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <div className="flex justify-between items-start mb-6 border-b border-slate-100 pb-4">
                           <div>
                             <h2 className="text-2xl font-bold text-emerald-800">VOC-2026-0001</h2>
                             <p className="text-sm text-slate-500 mt-1">วันที่แจ้ง: 25 มิ.ย. 2026, 14:20 น. • จาก: ไม่เปิดเผยตัวตน</p>
                           </div>
                           <div className="text-right">
                             <select className="bg-red-50 text-red-600 border border-red-200 text-xs px-3 py-1.5 rounded-md font-bold outline-none cursor-pointer" defaultValue="ระดับความเร่งด่วน: สูง">
                               <option>ระดับความเร่งด่วน: ต่ำ</option>
                               <option>ระดับความเร่งด่วน: ปานกลาง</option>
                               <option>ระดับความเร่งด่วน: สูง</option>
                               <option>ระดับความเร่งด่วน: วิกฤต</option>
                             </select>
                             <p className="text-[10px] text-slate-400 mt-1">(ปรับโดย Admin)</p>
                           </div>
                        </div>

                        <div className="space-y-5">
                          <div>
                            <h3 className="text-sm font-bold text-slate-800 mb-2">ประเภทเรื่อง</h3>
                            <p className="text-sm font-medium text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100 inline-block">ปัญหาการปฏิบัติงาน</p>
                          </div>
                          <div>
                            <h3 className="text-sm font-bold text-slate-800 mb-2">รายละเอียด</h3>
                            <p className="text-sm text-slate-700 bg-slate-50 p-4 rounded-xl border border-slate-200 leading-relaxed">
                              แอร์ในห้องพักคอยญาติหน้า ER ไม่เย็นเลยครับ คนไข้และญาติมารอเยอะมาก อากาศร้อน อบอ้าว ทำให้รู้สึกไม่สบายตัว และเสี่ยงต่อการติดเชื้อทางเดินหายใจได้ง่ายขึ้นครับ รบกวนตรวจสอบด่วน
                            </p>
                          </div>
                          <div className="grid grid-cols-2 gap-4">
                             <div>
                               <h3 className="text-sm font-bold text-slate-800 mb-2">สถานที่ / หน่วยงาน</h3>
                               <p className="text-sm text-slate-600 bg-slate-50 px-3 py-2 rounded-lg border border-slate-100">แผนกฉุกเฉิน (ER)</p>
                             </div>
                             <div>
                               <h3 className="text-sm font-bold text-slate-800 mb-2">ผลกระทบที่เกิดขึ้น</h3>
                               <ul className="text-sm text-slate-600 list-disc list-inside bg-slate-50 px-3 py-2 rounded-lg border border-slate-100">
                                 <li>กระทบผู้ป่วย</li>
                                 <li>กระทบคุณภาพบริการ</li>
                               </ul>
                             </div>
                          </div>
                        </div>
                     </div>

                     <div className="bg-emerald-50/50 rounded-2xl border border-emerald-200 p-6 shadow-sm">
                        <h3 className="text-lg font-bold text-emerald-900 mb-4 flex items-center gap-2">
                          <Settings size={20} className="text-emerald-600" /> การจัดการ (Admin Action)
                        </h3>
                        
                        <div className="space-y-4">
                           <div className="grid grid-cols-2 gap-4">
                              <div>
                                <label className="block text-sm font-semibold text-slate-700 mb-1">Status (สถานะปัจจุบัน)</label>
                                <select className="w-full p-2.5 bg-white border border-emerald-200 rounded-xl text-sm outline-none focus:border-emerald-500 font-medium text-emerald-800" defaultValue="อยู่ระหว่างดำเนินการ">
                                  <option>รับเรื่องแล้ว</option>
                                  <option>อยู่ระหว่างพิจารณา</option>
                                  <option>อยู่ระหว่างดำเนินการ</option>
                                  <option>ดำเนินการแล้ว</option>
                                  <option>ปิดเรื่อง</option>
                                </select>
                              </div>
                              <div>
                                <label className="block text-sm font-semibold text-slate-700 mb-1">ผู้รับผิดชอบ</label>
                                <input type="text" defaultValue="ฝ่ายซ่อมบำรุง" className="w-full p-2.5 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:border-emerald-500" />
                              </div>
                           </div>

                           <div>
                              <label className="block text-sm font-semibold text-slate-700 mb-1">Root Cause (สาเหตุรากฐาน)</label>
                              <textarea rows="2" className="w-full p-3 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:border-emerald-500" placeholder="ระบุสาเหตุของปัญหา..."></textarea>
                           </div>
                           <div>
                              <label className="block text-sm font-semibold text-slate-700 mb-1">Action Taken (การแก้ไข)</label>
                              <textarea rows="2" className="w-full p-3 bg-white border border-slate-200 rounded-xl text-sm outline-none focus:border-emerald-500" placeholder="สิ่งที่ได้ดำเนินการไปแล้ว..."></textarea>
                           </div>
                           
                           <div className="pt-2 border-t border-emerald-200/60">
                              <label className="block text-sm font-semibold text-emerald-800 mb-1">Feedback to Reporter (ปิด Loop การสื่อสาร)</label>
                              <p className="text-xs text-emerald-600 mb-2">ข้อความนี้จะไปแสดงในหน้าติดตามสถานะของผู้ใช้ เพื่อตอบกลับและแจ้งผลให้ผู้ส่งทราบ</p>
                              <textarea rows="3" defaultValue="รับทราบปัญหาเรื่องแอร์ห้องพักญาติไม่เย็นครับ ตอนนี้ได้แจ้งช่างซ่อมบำรุงเข้าตรวจสอบแล้ว เบื้องต้นพบน้ำยาแอร์ขาด กำลังเติมน้ำยาครับ" className="w-full p-3 bg-white border border-emerald-300 rounded-xl text-sm outline-none focus:border-emerald-500 shadow-inner text-slate-700"></textarea>
                           </div>

                           <div className="pt-4 flex justify-end">
                              <button className="bg-emerald-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-emerald-700 shadow-md shadow-emerald-200 transition">
                                บันทึกการอัปเดต
                              </button>
                           </div>
                        </div>
                     </div>
                  </div>

                  <div className="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 h-fit">
                     <h3 className="text-sm font-bold text-slate-800 mb-4 border-b border-slate-100 pb-2">ประวัติการอัปเดต (Activity Log)</h3>
                     <div className="space-y-4 relative before:absolute before:inset-0 before:ml-2.5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 before:to-transparent">
                        <div className="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                          <div className="flex items-center justify-center w-6 h-6 rounded-full border-2 border-white bg-orange-400 text-white shadow shrink-0 z-10"></div>
                          <div className="w-[calc(100%-2.5rem)] ml-4 md:ml-0 md:px-4 md:w-1/2 md:text-right">
                             <p className="text-xs font-bold text-slate-800">เปลี่ยนสถานะ: อยู่ระหว่างดำเนินการ</p>
                             <p className="text-[11px] text-slate-500 mt-0.5">โดย Admin - วันนี้, 09:15 น.</p>
                          </div>
                        </div>
                        <div className="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                          <div className="flex items-center justify-center w-6 h-6 rounded-full border-2 border-white bg-slate-300 text-white shadow shrink-0 z-10"></div>
                          <div className="w-[calc(100%-2.5rem)] ml-4 md:ml-0 md:px-4 md:w-1/2">
                             <p className="text-xs font-bold text-slate-800">ระบบสร้าง Ticket ใหม่</p>
                             <p className="text-[11px] text-slate-500 mt-0.5">25 มิ.ย. 2026, 14:20 น.</p>
                          </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
          );
        }

        function ReportsView() {
          return (
            <div className="space-y-6 animate-in fade-in">
              <h1 className="text-2xl font-bold text-slate-800">Reports & Analytics</h1>
              <p className="text-slate-500 text-sm">ระบบออกรายงานและวิเคราะห์ข้อมูลเชิงลึก</p>
              
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="bg-white p-6 md:p-8 rounded-2xl border border-slate-200 shadow-sm flex flex-col items-center justify-center text-center min-h-[320px]">
                  <div className="w-16 h-16 bg-emerald-50 rounded-full flex items-center justify-center text-emerald-600 mb-6">
                    <FileText size={32} />
                  </div>
                  <h3 className="text-lg font-bold text-slate-800 mb-2">ส่งออกรายงาน (Export Data)</h3>
                  <p className="text-sm text-slate-500 mb-8 max-w-sm">ดาวน์โหลดข้อมูล VOC ทั้งหมดเป็นไฟล์ดิบ CSV หรือ Excel เพื่อนำไปวิเคราะห์ต่อในโปรแกรมจัดการอื่นๆ</p>
                  <button className="bg-emerald-600 text-white px-8 py-3 rounded-xl text-sm font-bold hover:bg-emerald-700 shadow-lg shadow-emerald-200/50 transition">
                    ดาวน์โหลดรายงาน (.CSV / .Excel)
                  </button>
                </div>

                <div className="bg-white p-6 md:p-8 rounded-2xl border border-slate-200 shadow-sm flex flex-col items-center justify-center text-center min-h-[320px]">
                  <div className="w-16 h-16 bg-orange-50 rounded-full flex items-center justify-center text-orange-500 mb-6">
                    <LayoutDashboard size={32} />
                  </div>
                  <h3 className="text-lg font-bold text-slate-800 mb-2">รายงานสรุปรายไตรมาส</h3>
                  <p className="text-sm text-slate-500 mb-6 max-w-sm">สร้างรายงานสรุปผลการดำเนินงานแบบอัตโนมัติ (PDF) แยกตามไตรมาส สำหรับนำเสนอผู้บริหาร</p>
                  <select className="w-full max-w-xs p-3 bg-slate-50 border border-slate-200 rounded-xl text-sm outline-none mb-4 cursor-pointer focus:border-orange-500" defaultValue="ไตรมาส 2 (เม.ย. พ.ค. มิ.ย.)">
                    <option>ไตรมาส 1 (ม.ค. ก.พ. มี.ค.)</option>
                    <option>ไตรมาส 2 (เม.ย. พ.ค. มิ.ย.)</option>
                    <option>ไตรมาส 3 (ก.ค. ส.ค. ก.ย.)</option>
                    <option>ไตรมาส 4 (ต.ค. พ.ย. ธ.ค.)</option>
                  </select>
                  <button className="w-full max-w-xs border-2 border-slate-200 text-slate-700 px-6 py-3 rounded-xl text-sm font-bold hover:bg-slate-50 hover:border-slate-300 transition">
                    สร้างและพิมพ์รายงาน (PDF)
                  </button>
                </div>
              </div>
            </div>
          );
        }

        function SettingsView() {
          return (
            <div className="space-y-6 animate-in fade-in">
              <h1 className="text-2xl font-bold text-slate-800">System Settings</h1>
              <p className="text-slate-500 text-sm">ตั้งค่าระบบและการจัดการผู้ใช้งาน</p>

              <div className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div className="p-6 border-b border-slate-100">
                  <h3 className="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <Settings size={20} className="text-emerald-600" />
                    ตั้งค่าทั่วไป (General Settings)
                  </h3>
                </div>
                <div className="p-6 space-y-6">
                  <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-6">
                    <div>
                      <h4 className="font-semibold text-slate-800">เวลามาตรฐานการตอบกลับ (SLA)</h4>
                      <p className="text-sm text-slate-500 mt-1 max-w-lg">กำหนดเวลาเป้าหมาย (Response Time) ที่แอดมินต้องตอบรับ Ticket เพื่อใช้คำนวณในแดชบอร์ด</p>
                    </div>
                    <div className="flex items-center gap-2">
                      <input type="number" defaultValue="24" className="w-20 p-2 border border-slate-200 rounded-lg text-center outline-none focus:border-emerald-500 font-medium" />
                      <span className="text-sm text-slate-600 font-medium">ชั่วโมง</span>
                    </div>
                  </div>

                  <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-6">
                    <div>
                      <h4 className="font-semibold text-slate-800">การแจ้งเตือน (Email Notifications)</h4>
                      <p className="text-sm text-slate-500 mt-1 max-w-lg">ส่งการแจ้งเตือนเข้า Email ของผู้รับผิดชอบเมื่อมีเรื่องใหม่ในหมวดหมู่ที่ดูแล</p>
                    </div>
                    <label className="relative inline-flex items-center cursor-pointer">
                      <input type="checkbox" className="sr-only peer" defaultChecked />
                      <div className="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                    </label>
                  </div>

                  <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-6">
                    <div>
                      <h4 className="font-semibold text-slate-800">จัดการหน่วยงาน (Departments Master Data)</h4>
                      <p className="text-sm text-slate-500 mt-1 max-w-lg">เพิ่ม แก้ไข หรือซ่อนรายชื่อแผนก/หน่วยงาน ที่แสดงเป็นตัวเลือกในหน้าฟอร์มผู้ใช้งาน</p>
                    </div>
                    <button className="px-6 py-2.5 bg-slate-100 text-slate-700 rounded-xl text-sm font-medium hover:bg-slate-200 transition">แก้ไขหน่วยงาน</button>
                  </div>

                  <div className="flex flex-col md:flex-row md:items-center justify-between gap-4 pt-2">
                    <div>
                      <h4 className="font-semibold text-slate-800">จัดการสิทธิ์ผู้ใช้งาน (Admin Roles)</h4>
                      <p className="text-sm text-slate-500 mt-1 max-w-lg">เพิ่มบัญชีเจ้าหน้าที่ กำหนดบทบาทว่าใครสามารถเห็นและจัดการ Ticket ของแผนกไหนได้บ้าง</p>
                    </div>
                    <button className="px-6 py-2.5 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-xl text-sm font-medium hover:bg-emerald-100 transition">จัดการเจ้าหน้าที่</button>
                  </div>
                </div>
              </div>
            </div>
          );
        }

        const root = ReactDOM.createRoot(document.getElementById('root'));
        root.render(<App />);
    </script>
</body>
</html>