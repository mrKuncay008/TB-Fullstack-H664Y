import React from 'react'

export default function Headerchat() {
  return (
    <div >
    <div className="py-2 px-3 bg-white flex flex-row justify-between items-center rounded-xl">
        <div className="flex items-center ">
                <div>
                    <img className="w-10 h-10 rounded-full" src="/public/img/robot.jpg"/>
                </div>
                <div className="ml-4">
                    <p className="text-grey-darkest">
                        Robot Kas Kota Tangerang
                    </p>
                    <p className="text-grey-darker text-xs mt-1">
                        Siap membantu anda sekarang
                    </p>
                </div>
            </div>
    </div>
</div>
  )
}
