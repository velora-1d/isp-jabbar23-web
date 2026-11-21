'use client';

import { useState } from 'react';
import { 
    BookOpen, 
    Plus, 
    Search, 
    Filter,
    Eye,
    Tag,
    Clock,
    MoreVertical,
    Edit2,
    Trash2,
    ExternalLink,
    CheckCircle2,
    XCircle
} from 'lucide-react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";

import { useKnowledgeBase, useDeleteArticle } from '@/hooks/use-support';
import { format } from 'date-fns';
import { id } from 'date-fns/locale';

export default function KnowledgeBasePage() {
    const [search, setSearch] = useState('');
    const [category, setCategory] = useState<string | undefined>(undefined);
    
    const { data: articlesData, isLoading } = useKnowledgeBase({
        search,
        category,
        per_page: 20
    });

    const deleteMutation = useDeleteArticle();

    const articles = articlesData?.data || [];

    const stats = {
        total: articles.length,
        published: articles.filter(a => a.is_published).length,
        totalViews: articles.reduce((acc, a) => acc + (a.views || 0), 0)
    };

    const categories = [
        { id: 'getting-started', label: 'Memulai', color: 'text-blue-400 bg-blue-500/10' },
        { id: 'billing', label: 'Billing', color: 'text-emerald-400 bg-emerald-500/10' },
        { id: 'technical', label: 'Teknis', color: 'text-purple-400 bg-purple-500/10' },
        { id: 'troubleshooting', label: 'Troubleshooting', color: 'text-amber-400 bg-amber-500/10' },
        { id: 'faq', label: 'FAQ', color: 'text-cyan-400 bg-cyan-500/10' },
    ];

    return (
        <div className="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
            {/* Header Section */}
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 className="text-3xl font-bold tracking-tight bg-gradient-to-r from-white to-zinc-400 bg-clip-text text-transparent">
                        Knowledge Base
                    </h1>
                    <p className="text-zinc-500 mt-1">
                        Kelola dokumentasi bantuan dan panduan teknis untuk pelanggan dan staf.
                    </p>
                </div>
                <div className="flex items-center gap-3">
                    <Button className="bg-blue-600 hover:bg-blue-500 text-white rounded-xl gap-2 shadow-lg shadow-blue-500/20">
                        <Plus className="h-4 w-4" />
                        Tulis Artikel
                    </Button>
                </div>
            </div>

            {/* Quick Stats */}
            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                <Card className="bg-zinc-900/50 border-zinc-800/50 relative overflow-hidden group">
                    <div className="absolute inset-0 bg-gradient-to-br from-blue-600/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <CardHeader className="pb-2">
                        <CardDescription className="text-zinc-500 text-xs font-semibold uppercase tracking-wider">Total Artikel</CardDescription>
                        <CardTitle className="text-3xl font-black text-white">{stats.total}</CardTitle>
                    </CardHeader>
                    <BookOpen className="absolute top-6 right-6 h-8 w-8 text-zinc-800" />
                </Card>

                <Card className="bg-zinc-900/50 border-zinc-800/50 relative overflow-hidden group">
                    <CardHeader className="pb-2">
                        <CardDescription className="text-zinc-500 text-xs font-semibold uppercase tracking-wider">Dipublikasikan</CardDescription>
                        <CardTitle className="text-3xl font-black text-emerald-400">{stats.published}</CardTitle>
                    </CardHeader>
                    <CheckCircle2 className="absolute top-6 right-6 h-8 w-8 text-zinc-800" />
                </Card>

                <Card className="bg-zinc-900/50 border-zinc-800/50 relative overflow-hidden group">
                    <CardHeader className="pb-2">
                        <CardDescription className="text-zinc-500 text-xs font-semibold uppercase tracking-wider">Total View</CardDescription>
                        <CardTitle className="text-3xl font-black text-white">{stats.totalViews.toLocaleString()}</CardTitle>
                    </CardHeader>
                    <Eye className="absolute top-6 right-6 h-8 w-8 text-zinc-800" />
                </Card>
            </div>

            {/* Filter & Search */}
            <Card className="bg-zinc-900/50 border-zinc-800/50 p-4">
                <div className="flex flex-col md:flex-row gap-4 items-center justify-between">
                    <div className="relative w-full md:w-96">
                        <Search className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-zinc-500" />
                        <Input 
                            placeholder="Cari judul atau isi artikel..." 
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                            className="pl-9 bg-zinc-950/50 border-zinc-800 focus:ring-blue-500/50"
                        />
                    </div>
                    <div className="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0 w-full md:w-auto">
                        <Button 
                            variant={category === undefined ? "secondary" : "ghost"}
                            size="sm"
                            onClick={() => setCategory(undefined)}
                            className="text-xs rounded-lg"
                        >
                            Semua
                        </Button>
                        {categories.map((cat) => (
                            <Button
                                key={cat.id}
                                variant={category === cat.id ? "secondary" : "ghost"}
                                size="sm"
                                onClick={() => setCategory(cat.id)}
                                className={`text-xs rounded-lg ${category === cat.id ? cat.color : "text-zinc-400"}`}
                            >
                                {cat.label}
                            </Button>
                        ))}
                    </div>
                </div>
            </Card>

            {/* Articles Table/Grid */}
            <div className="grid grid-cols-1 gap-4">
                {isLoading ? (
                    <div className="text-center py-20 text-zinc-500">Memuat artikel...</div>
                ) : articles.length === 0 ? (
                    <div className="text-center py-20 bg-zinc-900/20 border border-dashed border-zinc-800 rounded-3xl">
                        <BookOpen className="h-12 w-12 text-zinc-800 mx-auto mb-4" />
                        <h3 className="text-white font-medium">Belum ada artikel</h3>
                        <p className="text-zinc-500 text-sm">Mulai tulis panduan pertama Anda hari ini.</p>
                    </div>
                ) : (
                    articles.map((article) => (
                        <Card key={article.id} className="bg-zinc-900/30 border-zinc-800/50 hover:bg-zinc-900/50 transition-colors group">
                            <CardContent className="p-5 flex items-center justify-between gap-4">
                                <div className="flex items-start gap-4">
                                    <div className={`p-3 rounded-2xl ${
                                        categories.find(c => c.id === article.category)?.color || 'bg-zinc-800'
                                    }`}>
                                        <BookOpen className="h-6 w-6" />
                                    </div>
                                    <div>
                                        <div className="flex items-center gap-2 mb-1">
                                            <h3 className="text-white font-bold group-hover:text-blue-400 transition-colors">
                                                {article.title}
                                            </h3>
                                            {!article.is_published && (
                                                <Badge variant="outline" className="text-[10px] uppercase border-zinc-700 text-zinc-500">Draft</Badge>
                                            )}
                                        </div>
                                        <div className="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-zinc-500">
                                            <span className="flex items-center gap-1">
                                                <Tag className="h-3 w-3" />
                                                {article.category_label}
                                            </span>
                                            <span className="flex items-center gap-1">
                                                <Clock className="h-3 w-3" />
                                                {format(new Date(article.created_at), 'd MMM yyyy', { locale: id })}
                                            </span>
                                            <span className="flex items-center gap-1">
                                                <Eye className="h-3 w-3" />
                                                {article.views} views
                                            </span>
                                            {article.author && (
                                                <span className="flex items-center gap-1">
                                                    <Edit2 className="h-3 w-3" />
                                                    {article.author.name}
                                                </span>
                                            )}
                                        </div>
                                    </div>
                                </div>
                                
                                <div className="flex items-center gap-2">
                                    <Button variant="ghost" size="icon" className="text-zinc-500 hover:text-white rounded-xl">
                                        <ExternalLink className="h-4 w-4" />
                                    </Button>
                                    <DropdownMenu>
                                        <DropdownMenuTrigger asChild>
                                            <Button variant="ghost" size="icon" className="text-zinc-500 hover:text-white rounded-xl">
                                                <MoreVertical className="h-4 w-4" />
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent align="end" className="bg-zinc-900 border-zinc-800 text-zinc-400">
                                            <DropdownMenuItem className="focus:bg-zinc-800 focus:text-white gap-2">
                                                <Edit2 className="h-4 w-4" /> Edit Artikel
                                            </DropdownMenuItem>
                                            <DropdownMenuItem 
                                                className="focus:bg-red-500/10 focus:text-red-400 gap-2"
                                                onClick={() => deleteMutation.mutate(article.id)}
                                            >
                                                <Trash2 className="h-4 w-4" /> Hapus
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </div>
                            </CardContent>
                        </Card>
                    ))
                )}
            </div>
        </div>
    );
}
