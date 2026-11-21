import { useQuery } from "@tanstack/react-query";
import axios from "@/lib/axios";

export interface User {
  id: number;
  name: string;
  email: string;
  role?: string;
}

export interface UsersResponse {
  data: User[];
  total?: number;
}

export function useUsers(params: Record<string, any> = {}) {
  return useQuery<UsersResponse>({
    queryKey: ["users", params],
    queryFn: async () => {
      const response = await axios.get("/admin/users", { params });
      return response.data;
    },
  });
}
