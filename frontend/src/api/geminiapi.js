import axiosApi from "@/lib/axios";

export const PostApi = async (data) => {
    try {
      const response = await axiosApi.post("/api/tanya_ai/get_datatable", data);
      return {
        success: true,
        data: response.data
      };
    } catch (error) {
      return {
        success: false,
        error: error.response?.data?.message || "Server Error"
      };
    };
  };