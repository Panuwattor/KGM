import React, { useState } from 'react';

// Mock product data
const products = [
  {
    id: 1,
    name: 'กระโปรงนักเรียนหญิง ผ้าซีฟ่อน (สีกรมท่า)',
    tags: ['New', 'Sale'],
    price: 185,
    originalPrice: 250,
    description: 'กระโปรงนักเรียนหญิง ผ้าซีฟ่อน คุณภาพดี ใส่สบาย ไม่ร้อน เหมาะสำหรับนักเรียนทุกระดับชั้น ดีไซน์ทันสมัย ติทนทาน',
    sizes: ['22×24', '23×25', '24×26', '25×27', '26×28', '26×30', '26×32'],
    images: [
      'https://via.placeholder.com/600x600/1a3a2a/ffffff?text=Product+1',
      'https://via.placeholder.com/600x600/2d6a4f/ffffff?text=Product+1+Alt',
    ],
    thumbnail: 'https://via.placeholder.com/80x80/1a3a2a/ffffff?text=P1',
    stock: 83,
  },
  {
    id: 2,
    name: 'เสื้อนักเรียนหญิง แขนสั้น (สีขาว)',
    tags: ['Hot'],
    price: 220,
    originalPrice: null,
    description: 'เสื้อนักเรียนหญิง ผ้าโพลีเอสเตอร์ แห้งเร็ว ไม่ยับง่าย คุณภาพพรีเมียม',
    sizes: ['S', 'M', 'L', 'XL'],
    images: [
      'https://via.placeholder.com/600x600/2d6a4f/ffffff?text=Product+2',
      'https://via.placeholder.com/600x600/40916c/ffffff?text=Product+2+Alt',
    ],
    thumbnail: 'https://via.placeholder.com/80x80/2d6a4f/ffffff?text=P2',
    stock: 120,
  },
  {
    id: 3,
    name: 'กางเกงนักเรียนชาย ขายาว (สีกรมท่า)',
    tags: ['New'],
    price: 280,
    originalPrice: 320,
    description: 'กางเกงนักเรียนชาย ผ้าโพลีวูล ทรงสวย ใส่สบาย เนื้อผ้าคุณภาพเยี่ยม',
    sizes: ['28', '30', '32', '34', '36'],
    images: [
      'https://via.placeholder.com/600x600/40916c/ffffff?text=Product+3',
    ],
    thumbnail: 'https://via.placeholder.com/80x80/40916c/ffffff?text=P3',
    stock: 65,
  },
  {
    id: 4,
    name: 'ผ้าผูกหูกระต่าย นักเรียนชาย',
    tags: [],
    price: 45,
    originalPrice: null,
    description: 'ผ้าผูกหูกระต่าย สีกรมท่า ผ้าซาติน คุณภาพดี ผูกง่าย',
    sizes: ['One Size'],
    images: [
      'https://via.placeholder.com/600x600/52b788/ffffff?text=Product+4',
    ],
    thumbnail: 'https://via.placeholder.com/80x80/52b788/ffffff?text=P4',
    stock: 200,
  },
];

const ProductDetailPage = () => {
  const [selectedProduct, setSelectedProduct] = useState(products[0]);
  const [selectedImageIndex, setSelectedImageIndex] = useState(0);
  const [selectedSize, setSelectedSize] = useState(null);
  const [quantity, setQuantity] = useState(1);

  const handleProductClick = (product) => {
    setSelectedProduct(product);
    setSelectedImageIndex(0);
    setSelectedSize(null);
    setQuantity(1);
  };

  const handleQuantityChange = (delta) => {
    setQuantity(Math.max(1, Math.min(selectedProduct.stock, quantity + delta)));
  };

  const currentImage = selectedProduct.images[selectedImageIndex] || selectedProduct.images[0];
  const discountPercent = selectedProduct.originalPrice
    ? Math.round(((selectedProduct.originalPrice - selectedProduct.price) / selectedProduct.originalPrice) * 100)
    : 0;

  return (
    <div className="min-h-screen bg-gray-50 py-8 px-4">
      <div className="max-w-7xl mx-auto">
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
          {/* LEFT SIDEBAR - Product Thumbnails */}
          <div className="lg:col-span-2 order-2 lg:order-1">
            <div className="flex lg:flex-col gap-3 overflow-x-auto lg:overflow-y-auto lg:max-h-[600px] pb-2 lg:pb-0">
              {products.map((product) => (
                <button
                  key={product.id}
                  onClick={() => handleProductClick(product)}
                  className={`flex-shrink-0 w-20 h-20 rounded-xl overflow-hidden border-2 transition-all duration-200 hover:scale-105 ${
                    selectedProduct.id === product.id
                      ? 'border-green-600 ring-2 ring-green-200'
                      : 'border-gray-200 hover:border-green-400'
                  }`}
                >
                  <img
                    src={product.thumbnail}
                    alt={product.name}
                    className="w-full h-full object-cover"
                  />
                </button>
              ))}
            </div>
          </div>

          {/* CENTER - Main Product Image */}
          <div className="lg:col-span-5 order-1 lg:order-2">
            <div className="bg-white rounded-2xl shadow-lg overflow-hidden sticky top-4">
              <div className="aspect-square relative bg-gradient-to-br from-green-50 to-white">
                <img
                  src={currentImage}
                  alt={selectedProduct.name}
                  className="w-full h-full object-cover"
                />

                {/* Image Navigation Dots */}
                {selectedProduct.images.length > 1 && (
                  <div className="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                    {selectedProduct.images.map((_, index) => (
                      <button
                        key={index}
                        onClick={() => setSelectedImageIndex(index)}
                        className={`w-2.5 h-2.5 rounded-full transition-all ${
                          selectedImageIndex === index
                            ? 'bg-green-600 w-6'
                            : 'bg-white/60 hover:bg-white'
                        }`}
                      />
                    ))}
                  </div>
                )}

                {/* Product Tags */}
                {selectedProduct.tags.length > 0 && (
                  <div className="absolute top-4 right-4 flex flex-col gap-2">
                    {selectedProduct.tags.includes('New') && (
                      <span className="bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                        New
                      </span>
                    )}
                    {selectedProduct.tags.includes('Sale') && (
                      <span className="bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                        Sale -{discountPercent}%
                      </span>
                    )}
                    {selectedProduct.tags.includes('Hot') && (
                      <span className="bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                        Hot
                      </span>
                    )}
                  </div>
                )}
              </div>
            </div>
          </div>

          {/* RIGHT - Product Details & Checkout */}
          <div className="lg:col-span-5 order-3">
            <div className="bg-white rounded-2xl shadow-lg p-6 lg:p-8 space-y-6">
              {/* Product Title */}
              <div>
                <h1 className="text-2xl lg:text-3xl font-bold text-gray-900 leading-tight">
                  {selectedProduct.name}
                </h1>
              </div>

              {/* Price Section */}
              <div className="flex items-baseline gap-3">
                <span className="text-3xl lg:text-4xl font-bold text-green-700">
                  ฿{selectedProduct.price.toLocaleString()}
                </span>
                {selectedProduct.originalPrice && (
                  <span className="text-lg text-gray-400 line-through">
                    ฿{selectedProduct.originalPrice.toLocaleString()}
                  </span>
                )}
              </div>

              <div className="h-px bg-gray-200" />

              {/* Description */}
              <div>
                <h3 className="text-sm font-semibold text-gray-700 mb-2">รายละเอียดสินค้า</h3>
                <p className="text-gray-600 leading-relaxed text-sm">
                  {selectedProduct.description}
                </p>
              </div>

              {/* Size Selector */}
              <div>
                <div className="flex items-center justify-between mb-3">
                  <h3 className="text-sm font-semibold text-gray-700">
                    ไซส์: {selectedSize ? <span className="text-green-600">{selectedSize}</span> : <span className="text-red-500">*</span>}
                  </h3>
                </div>
                <div className="grid grid-cols-4 sm:grid-cols-5 lg:grid-cols-4 xl:grid-cols-5 gap-2">
                  {selectedProduct.sizes.map((size) => (
                    <button
                      key={size}
                      onClick={() => setSelectedSize(size)}
                      className={`px-3 py-2.5 rounded-lg border-2 text-sm font-semibold transition-all hover:scale-105 ${
                        selectedSize === size
                          ? 'border-green-600 bg-green-50 text-green-700 shadow-md'
                          : 'border-gray-200 text-gray-700 hover:border-green-400'
                      }`}
                    >
                      {size}
                    </button>
                  ))}
                </div>
              </div>

              {/* Quantity Selector */}
              <div>
                <h3 className="text-sm font-semibold text-gray-700 mb-3">จำนวน</h3>
                <div className="flex items-center gap-4">
                  <div className="flex items-center border-2 border-gray-200 rounded-lg overflow-hidden">
                    <button
                      onClick={() => handleQuantityChange(-1)}
                      disabled={quantity <= 1}
                      className="px-4 py-2 bg-gray-50 text-gray-700 font-bold hover:bg-gray-100 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
                    >
                      −
                    </button>
                    <input
                      type="number"
                      value={quantity}
                      onChange={(e) => setQuantity(Math.max(1, Math.min(selectedProduct.stock, parseInt(e.target.value) || 1)))}
                      className="w-16 px-3 py-2 text-center font-semibold text-gray-900 border-none focus:outline-none focus:ring-0"
                      min="1"
                      max={selectedProduct.stock}
                    />
                    <button
                      onClick={() => handleQuantityChange(1)}
                      disabled={quantity >= selectedProduct.stock}
                      className="px-4 py-2 bg-gray-50 text-gray-700 font-bold hover:bg-gray-100 disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
                    >
                      +
                    </button>
                  </div>
                  <span className="text-sm text-gray-500">
                    สินค้าคงเหลือ: <span className="font-semibold text-gray-700">{selectedProduct.stock} ชิ้น</span>
                  </span>
                </div>
              </div>

              <div className="h-px bg-gray-200" />

              {/* Total Price */}
              <div className="flex items-center justify-between px-4 py-3 bg-green-50 rounded-lg">
                <span className="text-sm font-semibold text-gray-700">ราคารวม</span>
                <span className="text-2xl font-bold text-green-700">
                  ฿{(selectedProduct.price * quantity).toLocaleString()}
                </span>
              </div>

              {/* Buy Now Button */}
              <button
                disabled={!selectedSize}
                className="w-full py-4 bg-gradient-to-r from-green-600 to-green-700 text-white font-bold text-lg rounded-xl shadow-lg hover:from-green-700 hover:to-green-800 transform hover:scale-[1.02] active:scale-[0.98] transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center gap-2"
              >
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                {selectedSize ? 'ซื้อเลย' : 'กรุณาเลือกไซส์'}
              </button>

              {/* Add to Cart Button */}
              <button
                disabled={!selectedSize}
                className="w-full py-3 border-2 border-green-600 text-green-700 font-semibold rounded-xl hover:bg-green-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
              >
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                เพิ่มลงตะกร้า
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ProductDetailPage;
