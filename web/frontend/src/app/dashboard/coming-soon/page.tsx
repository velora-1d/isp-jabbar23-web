import { DashboardPageShell } from "@/components/dashboard/page-shell";
import { Timer } from "lucide-react";

export default function ComingSoonPage() {
    return (
        <DashboardPageShell
            title="Feature Coming Soon"
            description="Modul ini sedang dalam tahap pengembangan."
        >
            <div className="flex flex-col items-center justify-center min-h-[50vh] space-y-6">
                <div className="p-6 rounded-full bg-emerald-500/10 border border-emerald-500/20 animate-pulse">
                    <Timer className="h-16 w-16 text-emerald-500" />
                </div>
                <div className="text-center space-y-2">
                    <h2 className="text-2xl font-bold text-zinc-100 font-heading">Sabar Ya!</h2>
                    <p className="text-zinc-400 max-w-md mx-auto">
                        Kami sedang menyiapkan modul ini dengan standar estetika futuristik terbaik.
                        Pantau terus progresnya di Master Tracker.
                    </p>
                </div>
                <button 
                    onClick={() => window.history.back()}
                    className="px-6 py-2 rounded-lg bg-emerald-600 text-white font-medium hover:bg-emerald-500 transition-all shadow-lg shadow-emerald-600/20"
                >
                    Kembali
                </button>
            </div>
        </DashboardPageShell>
    );
}
