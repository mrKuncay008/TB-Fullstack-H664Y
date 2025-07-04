import axiosApi from "@/lib/axios";

export const getYears = async (setYears) => {
  try {
    const response = await axiosApi.get('/api/get-years');
    
    if (response.status === 200) {
      setYears(response.data);

    } else {
      throw new Error('Failed to fetch years');
    }

  } catch (error) {
    console.error("Error fetching years:", error);
  }
};

export const downloadExcel = async (year) => {
  try {
    const response = await axiosApi.get(`/api/export-excel?year=${year}`, {
      responseType: 'blob',
    });

      const url = window.URL.createObjectURL(response.data);
      const a = document.createElement('a');
      a.href = url;
      a.download = `Pembukuan_Kas_Tahun_${year}.xlsx`;
      a.click();
      window.URL.revokeObjectURL(url);

  } catch (error) {
    console.error("Error downloading Excel file:", error);
  }
};
